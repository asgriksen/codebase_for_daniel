<?php

namespace App\Listeners;

use App\Events\AdSetWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateFacebookAdSet
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AdSetWasUpdated  $event
     * @return void
     */
    public function handle(AdSetWasUpdated $event)
    {
        // Tap into the ad set object.
        //
        $adSet = $event->adSet;

        // Now we will update the ad campaign record inside Facebook.
        //
        $adSet->accessFacebook()->updateAdSet( $adSet->fb_adset_id, $adSet->toArray() );
    }
}
