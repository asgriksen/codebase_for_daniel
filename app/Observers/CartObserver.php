<?php

namespace App\Observers;

use App\Cart;
use App\Events\CartWasCreated;

class CartObserver
{
    /**
     * Observe when a new cart record has been created.
     *
     * @param Cart $cart
     */
    public function created( Cart $cart )
    {
        // Fire the cart was created event.
        //
        event( new CartWasCreated( $cart ) );
    }
}