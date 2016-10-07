<?php

namespace App\Listeners;

use App\Contracts\Repositories\CampaignRepository;
use App\Events\CampaignWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateFacebookAdCampaign
{
    /**
     * Handle the event.
     *
     * @param  CampaignWasUpdated  $event
     * @return void
     */
    public function handle(CampaignWasUpdated $event)
    {
        // Tap into the campaign object.
        //
        $campaign = $event->campaign;

        // Now we will update the ad campaign record inside Facebook.
        //
        $campaign->accessFacebook()->updateAdCampaign( $campaign->fb_campaign_id, $campaign->toArray() );
    }
}
