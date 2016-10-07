<?php 

namespace App\Observers;

use App\Events\CampaignWasCreated;
use App\Events\CampaignWasUpdated;

class CampaignObserver
{
    /**
     * Observe when a campaign record has been created.
     *
     * @param $campaign
     */
    public function created( $campaign )
    {
        /*
         * @author Jordan Dalton <jordan.dalton@ymail.com>
         *
         * If the Facebook field(s) are empty then we will need to
         * fire the CampaignWasCreated event which will then
         * create a new ad campaign within Facebook.
         *
         * Why make this a logical condition?
         *
         * Well, when the user signs up and allows us to import their ad account
         * we will also be importing related campaigns at that time as well. So
         * when the campaign record gets imported the script will still hit the
         * CampaignObserver::created() event. So having this logical condition added
         * will allow us to only create new Facebook ad campaigns only when needed. Without
         * this check we could have duplicates in the system and on Facebook.
         */
        if( empty( $campaign->getFacebookIdFields() ) )
        {
            event( new CampaignWasCreated( $campaign ) );
        }
    }

    /**
     * Observe when a campaign record has been updated.
     *
     * @param $campaign
     */
    public function updated( $campaign )
    {
        // We will only fire the event as long as
        // there is existing facebook data.
        //
        if( ! empty( $campaign->getFacebookIdFields() ) )
        {
            event( new CampaignWasUpdated( $campaign ) );
        }
    }
}