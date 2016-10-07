<?php

namespace App\Events;

use App\Account;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AccountWasCreated extends Event
{
    use SerializesModels;

    /**
     * @var Account
     */
    public $account;

    /**
     * Create a new event instance.
     *
     * @param Account $account
     */
    public function __construct( Account $account )
    {
        $this->account = $account;
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
