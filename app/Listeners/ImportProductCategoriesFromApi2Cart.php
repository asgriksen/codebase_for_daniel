<?php

namespace App\Listeners;

use App\Contracts\Repositories\CartCategoryRepository;
use App\Events\ProductWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportProductCategoriesFromApi2Cart //implements ShouldQueue
{
    //use InteractsWithQueue;

    /**
     * @var CartCategoryRepository
     */
    private $cartCategoryRepository;

    /**
     * Create the event listener.
     *
     * @param CartCategoryRepository $cartCategoryRepository
     */
    public function __construct( CartCategoryRepository $cartCategoryRepository )
    {
        $this->cartCategoryRepository = $cartCategoryRepository;
    }

    /**
     * Handle the event.
     *
     * @param  ProductWasCreated  $event
     * @return void
     */
    public function handle(ProductWasCreated $event)
    {
        // If Etsy do nothing.
        if( $event->product->cart->isEtsy() ) return;

        // Access product's cart Api2Cart category API.
        $api = $event->product->cart->connectWithApi2CartCategoryApi();

        // Cart category ids container.
        $cart_category_ids = [];

        // Parent category ids.
        $parent_category_ids = [];

        // Obtain product's category id numbers.
        $category_ids = $event->product->categories_ids;

        // Obtain the product record.
        $product = $event->product;

        // Iterate through the category ids.
        foreach( $category_ids as $category_id )
        {
            // Fetch the category information from the api.
            $results = $api->getInfo( $category_id );

            // Skip if there was an error.
            if( get_class($results) === 'Exception' ) continue;

            // Map the results.
            $results = array_map($this->arrayMapClosure(), (array) $results);

            // Set the attributes we want to store in the record.
            $attributes = [
                'name' => $results['name']
            ];

            // Create a new cart category record.
            $cart_category = $this->cartCategoryRepository->firstOrCreateForCart( $event->product->cart, $attributes );

            // Add the cart category id to our list of cart categoy ids.
            $cart_category_ids[] = $cart_category->id;

            // Capture the parent id.
            $parent_id = (int) $results['parent_id'];

            // If there is a parent id we need to capture it and it's category
            // associate so that we can properly assign parent/child relationships.
            //
            if( $parent_id > 0 )
            {
                $parent_category_ids[$parent_id] = $cart_category->id;
            }

        }

        // Now sync the cart category ids with the product.
        $this->cartCategoryRepository->syncCategoriesWithProduct( $cart_category_ids, $product );

        // Now iterate through the parent category ids.
        foreach( $parent_category_ids as $id => $child_category_id )
        {
            // Fetch the category information from the api.
            $results = $api->getInfo( $id );

            // Skip if there was an error.
            if( get_class($results) === 'Exception' ) continue;

            // Map the results.
            $results = array_map($this->arrayMapClosure(), (array) $results);

            // Set the attributes we want to store in the record.
            $attributes = [
                'name' => $results['name']
            ];

            // Create a new cart category record.
            $parent = $this->cartCategoryRepository->firstOrCreateForCart( $event->product->cart, $attributes );

            // Now fetch the related category record.
            $child = $this->cartCategoryRepository->byId( $child_category_id );

            // Now associate the child with the parent record.
            $this->cartCategoryRepository->associateChildWithParent( $child, $parent );
        }
    }

    /**
     * Return the closure for the results array_map().
     *
     * @return \Closure
     */
    public function arrayMapClosure()
    {
        return function( $record )
        {
            return is_array( $record ) ? $record : html_entity_decode( $record );
        };
    }
}
