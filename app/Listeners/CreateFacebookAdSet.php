<?php

namespace App\Listeners;

use App\Contracts\Repositories\AdSetRepository;
use App\Events\AdSetWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateFacebookAdSet
{
    /**
     * @var AdSetRepository
     */
    private $adSetRepository;

    /**
     * Create the event listener.
     *
     * @param AdSetRepository $adSetRepository
     */
    public function __construct( AdSetRepository $adSetRepository )
    {
        $this->adSetRepository = $adSetRepository;
    }

    /**
     * Handle the event.
     *
     * @param  AdSetWasCreated  $event
     * @return void
     */
    public function handle(AdSetWasCreated $event)
    {
        // Access the ad set object.
        //
        $ad_set = $event->ad_set;

        // Access facebook from ad set's campaign account.
        //
        $facebook = $ad_set->accessFacebook();

        // Generate the facebook ad set record.
        //
        $ad_set_facebook_record = $facebook->createAdSet( $ad_set->toArray() );

        // Update the local ad set record with the ad set id that was returned from facebook.
        //
        $this->adSetRepository->update( $ad_set, $ad_set_facebook_record->toArray() );

    }
}
