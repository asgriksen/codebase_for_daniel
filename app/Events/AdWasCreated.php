<?php

namespace App\Events;

use App\Ad;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AdWasCreated extends Event
{
    use SerializesModels;

    /**
     * @var Ad
     */
    public $ad;

    /**
     * Create a new event instance.
     *
     * @param Ad $ad
     */
    public function __construct( Ad $ad )
    {
        $this->ad = $ad;
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
