<?php

namespace App\Repositories;

use App\Cart;
use App\Contracts\Repositories\ProductRepository as Contract;
use App\Product;
use App\Support\Repository\Traits\Repositories;
use App\User;

class ProductRepository implements Contract
{
    use Repositories;

    /**
     * @var Product
     */
    private $model;

    /**
     * @param Product $product
     */
    public function __construct( Product $product )
    {
        $this->model = $product;
    }

    /**
     * Fetch a specified product record within the users account.
     *
     * @param int  $id
     * @param User $user
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function byIdAndUser( $id, User $user )
    {
        return $user->products()->find( $id );
    }

    /**
     * Return all products for a specified user.
     *
     * @param User $user
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function byUser( User $user )
    {
        return $user->products;
    }

    /**
     * Return all products with related data for a specified user.
     *
     * @param User  $user
     * @param array $with
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function byUserWith( User $user, $with = [] )
    {
        return $user->products()->with( $with )->get();
    }

    /**
     * Fetch products by user that exist within a particular category.
     *
     * @param User  $user
     * @param       $category_id
     * @param array $with
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function byUserAndCategoryIdWith( User $user, $category_id, $with = [] )
    {
        $whereHasCategories = function( $query ) use( $category_id )
        {
            return $query->whereCartCategoryId( $category_id );
        };

        return $user->products()->with($with)->whereHas('categories', $whereHasCategories)->get();
    }

    /**
     * Create new cart product record.
     *
     * @param Cart  $cart
     * @param array $attributes
     *
     * @return mixed
     */
    public function createForCart( Cart $cart, $attributes = [] )
    {
        return $cart->products()->save( $this->newInstance($attributes) );
    }

    /**
     * Search for products within a specified Api2Cart
     *
     * @param Cart  $cart   The cart object.
     * @param array $params The search parameters.
     *
     * @return array
     */
    public function findCartProductsInApi2Cart( Cart $cart, $params = [] )
    {
        // Connect to the cart's Api2Cart product api.
        $api = $cart->connectWithApi2CartProductApi();

        // Search for products and return the results.
        return $api->find( $params );
    }

    /**
     * Search for products within a specified Etsy cart.
     *
     * @param Cart  $cart   The cart object.
     * @param array $params The search parameters.
     *
     * @return array
     */
    public function findCartProductsInEtsy( Cart $cart, $params = [] )
    {
        // Connect to the cart's Etsy api.
        $api = $cart->connectWithEtsyApi();

        // Search for products and return the results.
        return $api->getActiveListingsByKeywordsInTitle( $params['etsy_keywords'] );
    }

    /**
     * List products within a specified Api2Cart
     *
     * @param Cart  $cart   The cart object.
     * @param array $params The search parameters.
     *
     * @return array
     */
    public function listCartProductsFromApi2Cart( Cart $cart, $params = [] )
    {
        // Connect to the cart's Api2Cart product api.
        $api = $cart->connectWithApi2CartProductApi();

        // Search for products and return the results.
        return $api->getList( $params );
    }

    /**
     * Import specific product record(s) from Api2Cart.
     *
     * @param Cart  $cart The cart object.
     * @param array $ids  The product ids numbers in Api2Cart
     *
     * @return array
     */
    public function importCartProductsByIdFromApi2Cart( Cart $cart, $ids = [] )
    {
        $imported = [];

        // Connect to the cart's Api2Cart product api.
        $api = $cart->connectWithApi2CartProductApi();

        // Fields we want to read.
        $fields = [
            'avail_sale',
            'categories',
            'categories_ids',
            'description',
            'id',
            'images',
            'meta_description',
            'meta_keywords',
            'meta_title',
            'name',
            'ordered_count',
            'product_options',
            'product_variants',
            'quantity',
            'short_description',
            'special_price',
            'u_brand',
            'u_model',
            'url',
            'view_count'
        ];

        // The data parameters we want from Api2Cart.
        $params = implode(',', $fields);

        foreach( $ids as $id )
        {
            $product_record = $api->getInfo($id, $params);

            if( get_class($product_record) === 'Exception' ) continue;

            $product_record_array = (array) $product_record;
            $product_record_array = array_map(function($record)
            {
                return is_array($record) ? $record : html_entity_decode($record);
            }, $product_record_array);

            $data = $product_record_array;
            $data['cart_id']     = $cart->id;
            $data['external_id'] = $data['id'];

            // Create the product record.
            //
            $product = $this->create( $data );

            // Proceed if images exist
            //
            if( isSet($product_record->images) )
            {
                foreach($product_record->images as $images )
                {
                    foreach( $images as $image )
                    {
                        foreach($image as $record)
                        {
                            $product->images()->create((array) $record);
                        }
                    }
                }

                $product->load('images');
            }

            $imported[] = $product;
        }

        // Return all the imported records.
        //
        return $imported;
    }

    /**
     * Import specific product record(s) from Etsy.
     *
     * @param Cart  $cart The cart object.
     * @param array $ids  The listings ids numbers in Etsy
     *
     * @return array
     */
    public function importCartProductsByListingIdFromEtsy( Cart $cart, $ids = [] )
    {
        $imported = [];

        // Connect to the cart's Api2Cart product api.
        $api = $cart->connectWithEtsyApi();

        // Fields we want to read.
        $fields = [
            'categories'     => 'category_path',
            'categories_ids' => 'category_path_ids',
            'description'    => 'description',
            'external_id'    => 'listing_id',
            'images'         => 'Images',
            'meta_title'     => 'title',
            'name'           => 'title',
            'price'          => 'price',
            'quantity'       => 'quantity',
            'url'            => 'url',
            'view_count'     => 'views'
        ];

        foreach( $ids as $id )
        {
            $product_record = $api->getListingWithImages($id);

            // Skip of the record does not exist.
            if( ! $product_record['count'] ) continue;

            $product_record_array = (array) $product_record['results'][0];
            $product_record_array = array_map(function($record)
            {
                return is_array($record) ? $record : html_entity_decode($record);
            }, $product_record_array);

            $data = [];

            foreach( $fields as $key => $value )
            {
                $data[$key] = $product_record_array[$value];
            }

            $data['cart_id']     = $cart->id;

            // Create the product record.
            //
            $product = $this->create( $data );

            // Proceed if images exist
            //
            if( isSet($product_record_array['Images']) )
            {
                foreach($product_record_array['Images'] as $image )
                {
                    $mappings = [
                        'url_fullxfull' => 'http_path',
                        //''              => 'type',
                        //''              => 'mime-type',
                        //''              => 'alt',
                        //''              => 'avail',
                        //''              => 'file_name',
                        //''              => 'size',
                        'rank'          => 'sort_order',
                    ];

                    $record = [];

                    foreach( $mappings as $key => $value )
                    {
                        $record[$value] = $image[$key];
                    }

                    $record['avail'] = 'True';

                    $product->images()->create((array) $record);
                }

                $product->load('images');
            }

            $imported[] = $product;
        }

        // Return all the imported records.
        //
        return $imported;
    }

    /**
     * Return all products for a specified user.
     *
     * @param int  $id   The product record id.
     * @param User $user
     *
     * @return mixed
     */
    public function showByIdAndUser( $id, User $user )
    {
        return $user->products()->with('categories', 'images')->find( $id );
    }
}