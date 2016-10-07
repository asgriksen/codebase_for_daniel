<?php

namespace App\Repositories;

use App\Account;
use App\AdSet;
use App\Campaign;
use App\Contracts\Repositories\AdSetRepository as AdSetRepositoryContract;
use App\Contracts\Repositories\CampaignRepository;
use App\Support\Repository\Traits\Repositories;
use App\Http\Requests;
use App\Http\Requests\StoreAdWizardRequest;
use App\User;
use FacebookAds\Object\Fields\AdSetFields;

class AdSetRepository implements AdSetRepositoryContract
{
    use Repositories;

    /**
     * The ad set model.
     *
     * @var AdSet
     */
    protected $model;

    /**
     * @var CampaignRepository
     */
    protected $campaignRepository;

    /**
     * Create new AdSetRepository instance.
     *
     * @param AdSet              $adSet
     * @param CampaignRepository $campaignRepository
     */
    public function __construct( AdSet $adSet, CampaignRepository $campaignRepository )
    {
        $this->model              = $adSet;
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * Fetch all ad set records within a list of campaign ids.
     *
     * @param array $campaign_ids
     *
     * @return Collection
     */
    public function byCampaignIds( $campaign_ids = [] )
    {
        return $this->getModel()->whereIn('campaign_id', $campaign_ids)->get();
    }

    /**
     * Fetch ad set by its facebook ad set id number.
     *
     * @param int $fb_adset_id
     *
     * @return AdSet|null
     */
    public function byFbAdsetId( $fb_adset_id )
    {
        return $this->getModel()->whereFbAdsetId( $fb_adset_id )->first();
    }

    /**
     * Fetch ad sets from a particular account belonging to a particular user.
     *
     * @param int  $id
     * @param User $user
     * @param bool $return_array
     *
     * @return null
     */
    public function byAccountIdAndUser( $id, User $user, $return_array = true )
    {
        $account = $user->accounts()->find( $id );

        // Return null if the account doesn't exist.
        //
        if( ! $account ) return null;

        return $return_array ? $account->adSets->toArray() : $account->adSets;
    }

    /**
     * Return all ad sets for a particular account and user.
     *
     * @param int             $account_id     The id of the account.
     * @param User            $user           The user object.
     * @param array           $fields         The facebook fields we want returned.
     * @param string          $start_date     The metric start date in format yyyy-mm-dd.
     * @param string          $end_date       The metric end date in format yyyy-mm-dd.
     * @param bool|string|int $time_increment Metric data breakdown.
     *
     * @return array
     */
    public function byAccountIdAndUserWithMetricsByDate( $account_id, User $user, $fields = [], $start_date, $end_date, $time_increment )
    {
        // Fetch the ad sets.
        //
        $ad_sets = $this->byAccountIdAndUser( $account_id, $user, false );

        // Define array that will be later used as the output.
        //
        $output = [];

        // Iterate through the campaigns.
        //
        foreach( $ad_sets as $ad_set )
        {
            // Set the metric fields, start and end dates
            //
            $ad_set->setMetricFieldsAttribute($fields);
            $ad_set->setMetricStartDateAttribute($start_date);
            $ad_set->setMetricEndDateAttribute($end_date);
            $ad_set->setMetricTimeIncrementAttribute($time_increment);

            // Include the metrics in the array data.
            //
            $ad_set->setAppends(['metrics']);

            // Hide the account record from the model array.
            //
            $ad_set->setHidden(['account']);

            // Append the campaign data to our new output array.
            //
            $output[] = $ad_set->toArray();
        }

        return $output;
    }

    /**
     * Return a specific ad set account with in a particular
     * account owned by a particular user.
     *
     * @param int  $id
     * @param int  $account_id
     * @param User $user
     *
     * @return mixed
     */
    public function byIdWithinAccountIdAndUser( $id, $account_id, User $user )
    {
        $account = $this->byAccountIdAndUser($account_id, $user, false);

        if( ! $account ) return null;

        return $account->find( $id );
    }

    /**
     * Return all ad sets for a particular account and user.
     *
     * @param int             $id             The id of the ad set record.
     * @param int             $account_id     The id of the account record.
     * @param User            $user           The user object.
     * @param array           $fields         The facebook fields we want returned.
     * @param string          $start_date     The metric start date in format yyyy-mm-dd.
     * @param string          $end_date       The metric end date in format yyyy-mm-dd.
     * @param bool|string|int $time_increment Metric data breakdown.
     *
     * @return array
     */
    public function byIdWithinAccountIdAndUserWithMetricsByDate( $id, $account_id, User $user, $fields = [], $start_date, $end_date, $time_increment = false )
    {
        // Fetch the ad sets.
        //
        $ad_set = $this->byIdWithinAccountIdAndUser( $id, $account_id, $user );

        if( ! $ad_set ) return null;

        $ad_set->setMetricFieldsAttribute($fields);
        $ad_set->setMetricStartDateAttribute($start_date);
        $ad_set->setMetricEndDateAttribute($end_date);
        $ad_set->setMetricTimeIncrementAttribute($time_increment);

        // Include the metrics in the array data.
        //
        $ad_set->setAppends(['metrics']);

        // Hide the account record from the model array.
        //
        $ad_set->setHidden(['account', 'campaign']);

        // Return the ad set.
        //
        return $ad_set->toArray();
    }

    /**
     * Return all ad sets by the user.
     *
     * @param User            $user           The user object.
     * @param array           $fields         The facebook fields we want returned.
     * @param string          $start_date     The metric start date in format yyyy-mm-dd.
     * @param string          $end_date       The metric end date in format yyyy-mm-dd.
     * @param bool|string|int $time_increment Metric data breakdown.
     *
     * @return array
     */
    public function byUserWithMetricsByDate( User $user, $fields = [], $start_date, $end_date, $time_increment = false )
    {
        // First we will obtain the ids for all of the users selected accounts.
        //
        $selected_account_ids = $user->selected_accounts->lists('id')->toArray();

        // Now we will fetch the accounts.
        //
        $accounts = Account::with('adSets')->whereIn('id', $selected_account_ids)->get();

        // This array will contain all of the ad set ids.
        //
        $ad_set_ids = [];

        // Loop through all the accounts and obtain the id numbers for reach.
        //
        foreach( $accounts as $account )
        {
            $ad_set_ids = array_merge($ad_set_ids, $account->adSets->lists('id')->toArray());
        }

        // Fetch the ad set records.
        //
        $ad_sets = AdSet::whereIn('id', $ad_set_ids)->get();

        // Define array that will be later used as the output.
        //
        $output = [];

        // Iterate through the campaigns.
        //
        foreach( $ad_sets as $ad_set )
        {
            // Set the metric fields, start and end dates
            //
            $ad_set->setMetricFieldsAttribute($fields);
            $ad_set->setMetricStartDateAttribute($start_date);
            $ad_set->setMetricEndDateAttribute($end_date);
            $ad_set->setMetricTimeIncrementAttribute($time_increment);

            // Include the metrics in the array data.
            //
            $ad_set->setAppends(['metrics']);

            // Hide the account record from the model array.
            //
            $ad_set->setHidden(['account']);

            // Append the campaign data to our new output array.
            //
            $output[] = $ad_set->toArray();
        }

        return $output;
    }

    /**
     * Import ad set records.
     *
     * @param array $ad_sets
     *
     * @return bool
     */
    public function import( $ad_sets = [ ] )
    {
        foreach ( $ad_sets as $ad_set )
        {
            // Access the ad set data prepared for the eloquent model.
            //
            $data = $ad_set->getDataForModel();

            // Attempt to locate campaign record.
            //
            $campaign = $this->campaignRepository->byFbCampaignId( $data['fb_campaign_id'] );

            // Attempt to locate ad set record.
            //
            $ad_set_record = $this->byFbAdsetId( $data['fb_adset_id'] );

            // Create the ad set as long as the campaign record exists
            // but an ad set record does not.
            //
            if( $campaign && ! $ad_set_record )
            {
                // Create a new ad set instance.
                //
                $ad_set_instance = $this->newInstance( $data );

                // Save and and associate the record.
                //
                $campaign->adSets()->save( $ad_set_instance );

                // Skip to the next iteration.
                //
                continue;
            }

            // Otherwise update the existing ad set record.
            //
            $this->update( $ad_set_record, $data );
        }

        return TRUE;
    }


    /**
     * Create an adset
     *
     * @param StoreAdWizardRequest $request
     * @param Campaign $campaign
     *
     * @return array
     */
    public function create( StoreAdWizardRequest $request, Campaign $campaign ) {

        $adsets = [];

        $ads = $request->body()->ad_data;
        if(count($ads->right_ads->ads) > 0) {
            // create adset object on facebook
            $fbAdSet = new \FacebookAds\Object\AdSet(null, 'act_' . $campaign->account->fb_account_id );
            $fbAdSet->setData( $request->adSetFBData( $campaign->fb_campaign_id ) );
            $targeting = $request->adSetFBTargetingData();
            $targeting['page_types'] = [ 'rightcolumn' ];
            $fbAdSet->{AdSetFields::NAME} = $fbAdSet->{AdSetFields::NAME} . '_rightAd';
            $fbAdSet->{AdSetFields::TARGETING} = $targeting;
            $fbAdSet->create();

            // create adset object on the database
            $adsets['rightAd'] = new AdSet();
            $adsets['rightAd']->fill ( $request->adSetData() );
            $adsets['rightAd']->campaign_id = $campaign->id;
            $adsets['rightAd']->fb_campaign_id = $campaign->fb_campaign_id;
            $adsets['rightAd']->fb_adset_id = $fbAdSet->id;
            $adsets['rightAd']->save();
        }
        if(count($ads->news_ads->ads) > 0) {
            // create adset object on facebook
            $fbAdSet = new \FacebookAds\Object\AdSet(null, 'act_' . $campaign->account->fb_account_id );
            $fbAdSet->setData( $request->adSetFBData( $campaign->fb_campaign_id ) );
            $targeting = $request->adSetFBTargetingData();
            $targeting['page_types'] = [ 'desktopfeed', 'mobilefeed' ];
            if($ads->news_ads->show_mobile && !$ads->news_ads->show_desktop) $targeting['page_types'] = [ 'desktopfeed' ];
            else if(!$ads->news_ads->show_mobile && $ads->news_ads->show_desktop) $targeting['page_types'] = [ 'mobilefeed' ];
            
            $fbAdSet->{AdSetFields::NAME} = $fbAdSet->{AdSetFields::NAME} . '_newsAd';
            $fbAdSet->{AdSetFields::TARGETING} = $targeting;
            $fbAdSet->create();

            // create adset object on the database
            $adsets['newsAd'] = new AdSet();
            $adsets['newsAd']->fill ( $request->adSetData() );
            $adsets['newsAd']->campaign_id = $campaign->id;
            $adsets['newsAd']->fb_campaign_id = $campaign->fb_campaign_id;
            $adsets['newsAd']->fb_adset_id = $fbAdSet->id;
            $adsets['newsAd']->save();
        }

        if(count($ads->multi_products_ads->ads) > 0) {
            // create adset object on facebook
            $fbAdSet = new \FacebookAds\Object\AdSet(null, 'act_' . $campaign->account->fb_account_id );
            $fbAdSet->setData( $request->adSetFBData( $campaign->fb_campaign_id ) );
            $targeting = $request->adSetFBTargetingData();
            $targeting['page_types'] = [ 'desktopfeed', 'mobilefeed' ];
            if($ads->multi_products_ads->show_mobile && !$ads->multi_products_ads->show_desktop) $targeting['page_types'] = [ 'desktopfeed' ];
            else if(!$ads->multi_products_ads->show_mobile && $ads->multi_products_ads->show_desktop) $targeting['page_types'] = [ 'mobilefeed' ];
            
            $fbAdSet->{AdSetFields::NAME} = $fbAdSet->{AdSetFields::NAME} . '_multiProductsAd';
            $fbAdSet->{AdSetFields::TARGETING} = $targeting;
            $fbAdSet->create();

            // create adset object on the database
            $adsets['multiProductsAd'] = new AdSet();
            $adsets['multiProductsAd']->fill ( $request->adSetData() );
            $adsets['multiProductsAd']->campaign_id = $campaign->id;
            $adsets['multiProductsAd']->fb_campaign_id = $campaign->fb_campaign_id;
            $adsets['multiProductsAd']->fb_adset_id = $fbAdSet->id;
            $adsets['multiProductsAd']->save();
        }

        return $adsets;
    }
}