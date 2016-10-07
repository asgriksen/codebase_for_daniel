<?php

namespace App\Listeners;

use App\Contracts\Repositories\CampaignRepository;
use App\Events\CampaignWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateFacebookAdCampaign
{
    /**
     * @var CampaignRepository
     */
    private $campaignRepository;

    /**
     * Create the event listener.
     *
     * @param CampaignRepository $campaignRepository
     */
    public function __construct( CampaignRepository $campaignRepository )
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * Handle the event.
     *
     * @param  CampaignWasCreated  $event
     * @return void
     */
    public function handle( CampaignWasCreated $event )
    {
        // Tap into the campaign object.
        //
        $campaign = $event->campaign;

        // Now we will access campaign's parent account Facebook record.
        //
        $facebook = $campaign->accessFacebook();

        // Now we will create the ad campaign record inside Facebook.
        //
        $ad_campaign = $facebook->createAdCampaign( $campaign->toArray() );

        // Now we need to update the campaign record with the campaign
        // id number that was returned from facebook.
        //
        $this->campaignRepository->update( $campaign, ['fb_campaign_id' => $ad_campaign->id]);
    }
}