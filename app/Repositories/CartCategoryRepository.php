<?php

namespace App\Repositories;

use App\Cart;
use App\CartCategory;
use App\Contracts\Repositories\CartCategoryRepository as Contract;
use App\Product;
use App\Support\Repository\Traits\Repositories;
use App\User;

class CartCategoryRepository implements Contract
{
    use Repositories;

    /**
     * @var CartCategory
     */
    protected $model;

    /**
     * Create new CartCategoryRepository instance.
     *
     * @param CartCategory $cartCategory
     */
    public function __construct( CartCategory $cartCategory )
    {
        $this->model = $cartCategory;
    }

    /**
     * Fetch all categories with a cart owned by a particular user.
     *
     * @param Cart $cart
     * @param User $user
     *
     * @return Collection
     */
    public function byCartAndUser( Cart $cart, User $user )
    {
        return $user->carts()->find( $cart->id )->categories;
    }

    /*
     * Associate a child record with it's parent.
     *
     * @param CartCategory $child
     * @param CartCategory $parent
     *
     * @return boolean
     */
    public function associateChildWithParent( CartCategory $child, CartCategory $parent )
    {
        return $child->parent()->associate($parent)->save();
    }

    /**
     * Fetch the cart category record by a specified ID value.
     *
     * @param $id
     *
     * @return CartCategory
     */
    public function byId( $id )
    {
        return $this->getModel()->find( $id );
    }

    /**
     * Fetch or create a category record for a given cart.
     *
     * @param Cart  $cart
     * @param array $attributes
     *
     * @return CartCategory
     */
    public function firstOrCreateForCart( Cart $cart, $attributes = [] )
    {
        return $cart->categories()->firstOrCreate( $attributes );
    }

    /**
     * Sync cart category ids with a given product.
     *
     * @param array   $cart_category_ids
     * @param Product $product
     *
     * @return array
     */
    public function syncCategoriesWithProduct( $cart_category_ids = [], Product $product )
    {
        return $product->categories()->sync( $cart_category_ids );
    }

    /**
     * Fetch cart category record by id within the users known carts
     *
     * @param int  $id   The id of the target cart category record.
     * @param User $user The user object.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function byIdAndUser( $id, User $user ) 
    {
        return $user->cartCategories()->find( $id );
    }

    /**
     * Fetch all categories with a cart owned by a particular user.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function byCartAndUserWithNested( Cart $cart, User $user, $parentCategory )
    {
        $categories = $user
                        ->carts()
                        ->find($cart->id)
                        ->categories()
                        ->with('categories');

        if($parentCategory == null) {
            $categories = $categories->whereNull( 'category_id' );
        }
        else {
            $categories = $categories->where( 'category_id', $parentCategory );
        }
                        
        $categories = $categories->get();

        foreach($categories as &$category) {
            foreach($category->categories as $index => $subcategory) {
                $category->categories[$index] = $this->byIdAndUserWithNested( $subcategory->id, $cart, $user );
            }
        }

        return $categories;
    }


    /**
     * Fetch cart category record by id within the users known carts
     *
     * @param int  $id   The id of the target cart category record.
     * @param User $user The user object.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function byIdAndUserWithNested( $id, Cart $cart, User $user )
    {
        $category = $user->cartCategories()->find( $id );

        $category->categories = $this->byCartAndUserWithNested( $cart, $user, $id );

        return $category;
    }
}