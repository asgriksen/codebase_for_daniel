<?php

namespace App\Listeners;

use App\Events\CartWasCreated;
use App\Support\Api2Cart\Cart;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateApi2Cart
{
    /**
     * @var Cart
     */
    private $cart;

    /**
     * Create the event listener.
     *
     * @param Cart $cart
     */
    public function __construct( Cart $cart )
    {
        $this->cart = $cart;
    }

    /**
     * Handle the event.
     *
     * @param  CartWasCreated  $event
     * @return void
     */
    public function handle(CartWasCreated $event)
    {
        // Access the cart record.
        $cart = $event->cart;
    }
}
