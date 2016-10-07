<?php

namespace App\Listeners;

use App\Contracts\Repositories\CartCategoryRepository;
use App\Events\ProductWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportProductCategoriesFromEtsy //implements ShouldQueue
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
        // If Api2Cart do nothing.
        if( ! $event->product->cart->isEtsy() ) return;

        // Cart category ids container.
        $cart_category_ids = [];

        // Obtain product's category names.
        $categories = $event->product->getCategoriesArray();

        $parent_category_id = null;

        // Iterate through the category ids.
        foreach( $categories as $category )
        {
            // Set the attributes we want to store in the record.
            $attributes = [
                'name' => $category
            ];

            if( ! is_null($parent_category_id))
            {
                $attributes['category_id'] = $parent_category_id;
            }

            // Check if we already have a saves category matching that name.
            // Create a new cart category record.
            $cart_category = $this->cartCategoryRepository->firstOrCreateForCart( $event->product->cart, $attributes );

            // Add the cart category id to our list of cart categoy ids.
            $cart_category_ids[] = $cart_category->id;

            // Update the parent category id which will be applied to the next iteration.
            $parent_category_id = $cart_category->id;
        }

        // Now sync the cart category ids with the product.
        $this->cartCategoryRepository->syncCategoriesWithProduct( $cart_category_ids, $event->product );
    }
}
