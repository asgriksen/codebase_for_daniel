<?php

namespace App\Listeners;

use App\Contracts\Repositories\AdRepository;
use App\Events\AdWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateFacebookAd
{
    /**
     * @var AdRepository
     */
    private $adRepository;

    /**
     * Create the event listener.
     *
     * @param AdRepository $adRepository
     */
    public function __construct( AdRepository $adRepository )
    {
        $this->adRepository = $adRepository;
    }

    /**
     * Handle the event.
     *
     * @param  AdWasCreated  $event
     * @return void
     */
    public function handle(AdWasCreated $event)
    {
        // @todo Create ad on Facebook
    }
}
