<?php

namespace App\Listeners;

use App\Events\AdWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateFacebookAd
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
     * @param  AdWasUpdated  $event
     * @return void
     */
    public function handle(AdWasUpdated $event)
    {
        // Tap into the ad object.
        //
        $ad = $event->ad;

        // Now we will update the ad campaign record inside Facebook.
        //
        $ad->accessFacebook()->updateAdGroup( $ad->fb_ad_id, $ad->toArray() );
    }
}
