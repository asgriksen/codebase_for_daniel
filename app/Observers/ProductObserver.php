<?php

namespace App\Observers;

use App\Events\ProductWasCreated;
use App\Product;

class ProductObserver
{
    /**
     * Observe when a product is going through the creation process.
     *
     * @param Product $product
     */
    public function creating( Product $product )
    {
        $product->categories_ids = $this->removeDoubleQuotes( is_array($product->categories_ids) ? $product->categories_ids : [$product->categories_ids] );
    }

    /**
     * Observe when a product record has been created.
     *
     * @param Product $product
     */
    public function created( Product $product )
    {
        // Fire the product was create event.
        event(new ProductWasCreated($product));
    }

    /**
     * Observe when a product record has been created.
     *
     * @param Product $product
     */
    public function updated( Product $product )
    {
        // If there are no more products in inventory we will need
        // to suspend the campaign record associated with the product.
        //
        if( ! $product->quantity )
        {
            \Log::info('suspend ad for this product #' . $product->id);

            // @todo - Create such logic and tie it all together when able.
        }
    }

    /**
     * Define the closure that will strip double quotes.
     *
     * @return \Closure
     */
    private function getRemoveDoubleQuotesClosure()
    {
        return function( $record )
        {
            return trim($record, '"');
        };
    }

    /**
     * Remove double quotes from all array items.
     *
     * @param array $array
     *
     * @return array
     */
    private function removeDoubleQuotes( $array = [] )
    {
        return array_map($this->getRemoveDoubleQuotesClosure(), $array);
    }
}