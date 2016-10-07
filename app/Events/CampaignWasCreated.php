<?php

namespace App\Events;

use App\Campaign;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CampaignWasCreated extends Event
{
    use SerializesModels;

    /**
     * @var Campaign
     */
    public $campaign;

    /**
     * Create a new event instance.
     *
     * @param Campaign $campaign
     */
    public function __construct( Campaign $campaign )
    {
        $this->campaign = $campaign;
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
