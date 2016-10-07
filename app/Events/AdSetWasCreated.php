<?php

namespace App\Events;

use App\AdSet;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AdSetWasCreated extends Event
{
    use SerializesModels;

    /**
     * @var AdSet
     */
    private $adSet;

    /**
     * Create a new event instance.
     *
     * @param AdSet $adSet
     */
    public function __construct( AdSet $adSet )
    {
        $this->adSet = $adSet;
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
