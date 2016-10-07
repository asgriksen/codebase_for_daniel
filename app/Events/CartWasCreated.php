<?php

namespace App\Events;

use App\Cart;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CartWasCreated extends Event
{
    use SerializesModels;

    /**
     * @var Cart
     */
    public $cart;

    /**
     * Create a new event instance.
     *
     * @param Cart $cart
     */
    public function __construct( Cart $cart )
    {
        $this->cart = $cart;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
