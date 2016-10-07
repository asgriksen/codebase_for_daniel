<?php

namespace App\Repositories;

use App\Account;
use App\Ad;
use App\AdSet;
use App\User;
use App\Contracts\Repositories\AdRepository as Contract;
use App\Contracts\Repositories\AdSetRepository;
use App\Support\Repository\Traits\Repositories;
use App\Http\Requests\StoreAdWizardRequest;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\Fields\AdImageFields;
use FacebookAds\Object\Fields\AdCreativeFields;
use FacebookAds\Object\Fields\AdFields;
use FacebookAds\Object\ObjectStory\LinkData;
use FacebookAds\Object\Fields\ObjectStory\LinkDataFields;
use FacebookAds\Object\ObjectStorySpec;
use FacebookAds\Object\Fields\ObjectStorySpecFields;

class AdRepository implements Contract
{
    use Repositories;

    /**
     * The Ad model.
     *
     * @var Ad
     */
    private $model;

    /**
     * @var AdSetRepository
     */
    private $adSetRepository;

    /**
     * Create new AdRepository instance.
     *
     * @param Ad              $ad
     * @param AdSetRepository $adSetRepository
     */
    public function __construct( Ad $ad, AdSetRepository $adSetRepository )
    {
        $this->model           = $ad;
        $this->adSetRepository = $adSetRepository;
    }

    /**
     * Fetch all ad records within a list of ad set ids.
     *
     * @param array $ad_set_ids
     *
     * @return Collection
     */
    public function byAdSetIds( $ad_set_ids = [] )
    {
        return $this->getModel()->whereIn('ad_set_id', $ad_set_ids)->get();
    }

    /**
     * Fetch ad records by their ad set ID number within the users selected account ad sets.
     *
     * @param int  $ad_set_id  The id number of the ad set record.
     * @param User $user       The user object.
     * @param int  $account_id The id number of the selected account.
     *
     * @return Ad|null
     */
    public function byAdSetIdWithinUserAccountId( $ad_set_id, User $user, $account_id )
    {
        return $user->accounts()->find( $account_id )->adSets()->find( $ad_set_id )->ads;
    }

    /**
     * Fetch ad records with metric data by their ad set ID number within the users selected accounts ad sets.
     *
     * @param int             $ad_set_id      The id number of the ad set record.
     * @param User            $user           The user object.
     * @param int             $account_id     The id number of the selected account.
     * @param array           $fields         The faceook fields we want returned.
     * @param string          $start_date     The metric start date in format yyyy-mm-dd.
     * @param string          $end_date       The metric end date in format yyyy-mm-dd.
     * @param bool|string|int $time_increment Metric data breakdown.
     *
     * @return array
     */
    public function byAdSetIdWithinUserAccountIdWithMetricsByDate( $ad_set_id, User $user, $account_id, $fields = [ ], $start_date, $end_date, $time_increment = false )
    {
        $ads = $this->byAdSetIdWithinUserAccountId( $ad_set_id, $user, $account_id );

        // Define array that will be later used as the output.
        //
        $output = [ ];

        // Iterate through the campaigns.
        //
        foreach ( $ads as $ad )
        {
            // Set the metric fields, start and end dates
            //
            $ad->setMetricFieldsAttribute( $fields );
            $ad->setMetricStartDateAttribute( $start_date );
            $ad->setMetricEndDateAttribute( $end_date );
            $ad->setMetricTimeIncrementAttribute( $time_increment );

            // Include the metrics in the array data.
            //
            $ad->setAppends( [ 'metrics' ] );

            // Hide the account record from the model array.
            //
            $ad->setHidden( [
                'account',
                'ad_set'
            ] );

            // Append the campaign data to our new output array.
            //
            $output[ ] = $ad->toArray();
        }

        return $output;
    }

    /**
     * Fetch ad by both its ID and ad set ID within the users selected account ad sets.
     *
     * @param int  $id
     * @param int  $ad_set_id
     * @param User $user
     * @param int  $account_id
     *
     * @return mixed
     */
    public function byIdAndAdSetIdWithinUserAccountId( $id, $ad_set_id, User $user, $account_id )
    {
        return $user->accounts()->find( $account_id )->adSets()->find( $ad_set_id )->ads()->find( $id );
    }

    /**
     * Fetch ad set record by its ID number within the users selected accounts ad sets.
     *
     * @param int             $id             The id number of the ad record.
     * @param int             $ad_set_id      The id number of the ad set record.
     * @param User            $user           The user object.
     * @param int             $account_id     The id number of the selected account.
     * @param array           $fields         The faceook fields we want returned.
     * @param string          $start_date     The metric start date in format yyyy-mm-dd.
     * @param string          $end_date       The metric end date in format yyyy-mm-dd.
     * @param bool|string|int $time_increment Metric data breakdown.
     *
     * @return mixed
     */
    public function byIdAndAdSetIdWithinUserAccountIdWithMetricsByDate( $id, $ad_set_id, User $user, $account_id, $fields = [ ], $start_date, $end_date, $time_increment = false )
    {
        $ad = $this->byIdAndAdSetIdWithinUserAccountId( $id, $ad_set_id, $user, $account_id );

        // Set the metric fields, start and end dates
        //
        $ad->setMetricFieldsAttribute( $fields );
        $ad->setMetricStartDateAttribute( $start_date );
        $ad->setMetricEndDateAttribute( $end_date );
        $ad->setMetricTimeIncrementAttribute( $end_date );

        // Include the metrics in the array data.
        //
        $ad->setAppends( [ 'metrics' ] );

        // Hide the account record from the model array.
        //
        $ad->setHidden(['account', 'ad_set']);

        // Return the ad record.
        //
        return $ad->toArray();
    }

    /**
     * Import ads from facebook ad sets.
     *
     * @param array $ad_sets
     *
     * @return bool
     */
    public function importFromAdSets( $ad_sets = [] )
    {
        // Iterate through the provided ad sets.
        //
        foreach ( $ad_sets as $ad_set )
        {
            foreach ( $ad_set->getAds() as $ad )
            {
                // First we will check for the existence of an ad set record.
                //
                $ad_set_record = $this->adSetRepository->byFbAdsetId( $ad->getPrimaryObject()->getParentId() );

                // If the $ad_set_record exist we will proceed with creating the ad record.
                //
                if ( $ad_set_record )
                {
                    $instance = $this->newInstance( $ad->getDataForModel() );

                    $ad_set_record->ads()->save( $instance );
                }

            }
        }

        return TRUE;
    }

    /**
     * Return all ads by the user.
     *
     * @param User            $user           The user object.
     * @param array           $fields         The facebook fields we want returned.
     * @param string          $start_date     The metric start date in format yyyy-mm-dd.
     * @param string          $end_date       The metric end date in format yyyy-mm-dd.
     * @param bool|string|int $time_increment Metric data breakdown.
     *
     * @return array
     */
    public function byUserWithMetricsByDate( User $user, $fields = [ ], $start_date, $end_date, $time_increment = false )
    {
        // Fetch the users selected account ids.
        //
        $selected_account_ids = $user->selected_accounts()->lists('id')->toArray();

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

        // Now we will fetch all of the ads within hte list of ad set ids.
        //
        $ads = Ad::whereIn('ad_set_id', $ad_set_ids)->get();

        // Define array that will be later used as the output.
        //
        $output = [ ];

        // Iterate through the campaigns.
        //
        foreach ( $ads as $ad )
        {
            // Set the metric fields, start and end dates
            //
            $ad->setMetricFieldsAttribute( $fields );
            $ad->setMetricStartDateAttribute( $start_date );
            $ad->setMetricEndDateAttribute( $end_date );
            $ad->setMetricTimeIncrementAttribute( $time_increment );

            // Include the metrics in the array data.
            //
            $ad->setAppends([
                'age_statistics',
                'gender_statistics',
                'device_statistics',
                'placement_statistics',
                'keyword_statistics',
                'metrics'
            ]);

            // Hide the account record from the model array.
            //
            $ad->setHidden( [
                'account',
                'ad_set'
            ] );

            // Append the campaign data to our new output array.
            //
            $output[ ] = $ad->toArray();
        }

        return $output;
    }

    /**
     * Return all ads within a particular account owned by the user.
     *
     * @param int             $account_id     The account id number.
     * @param User            $user           The user object.
     * @param array           $fields         The facebook fields we want returned.
     * @param string          $start_date     The metric start date in format yyyy-mm-dd.
     * @param string          $end_date       The metric end date in format yyyy-mm-dd.
     * @param bool|string|int $time_increment Metric data breakdown.
     *
     * @return array
     */
    public function byAccountIdAndUserWithMetricsByDate( $account_id, User $user, $fields = [ ], $start_date, $end_date, $time_increment = false )
    {
        // Now we will fetch the specified selected account.
        //
        $account = $user->selected_accounts()->find( $account_id );

        // Bail if the account does not exist.
        //
        if( ! $account ) return null;

        // This array will contain all of the ad set ids.
        //
        $ad_set_ids = $account->adSets->lists('id')->toArray();

        // Now we will fetch all of the ads within hte list of ad set ids.
        //
        $ads = Ad::whereIn('ad_set_id', $ad_set_ids)->get();

        // Define array that will be later used as the output.
        //
        $output = [ ];

        // Iterate through the campaigns.
        //
        foreach ( $ads as $ad )
        {
            // Set the metric fields, start and end dates
            //
            $ad->setMetricFieldsAttribute( $fields );
            $ad->setMetricStartDateAttribute( $start_date );
            $ad->setMetricEndDateAttribute( $end_date );
            $ad->setMetricTimeIncrementAttribute( $time_increment );

            // Include the metrics in the array data.
            //
            $ad->setAppends([
                'age_statistics',
                'gender_statistics',
                'device_statistics',
                'placement_statistics',
                'keyword_statistics',
                'metrics'
            ]);

            // Hide the account record from the model array.
            //
            $ad->setHidden( [
                'account',
                'ad_set'
            ] );

            // Append the campaign data to our new output array.
            //
            $output[ ] = $ad->toArray();
        }

        return $output;
    }




    /**
     * Create an ad
     *
     * @param StoreAdWizardRequest $request
     * @param array $adsets
     *
     * @return FacebookAds\Object\Ad
     */
    public function create( StoreAdWizardRequest $request, $adsets ) {

        $creatives = [];
        $ads = $request->body()->ad_data;

        foreach($ads->right_ads->ads as $adBody) {
            // $fbObjImage = new AdImage(null, 'act_' . $adset['rightAd']->campaign->account->fb_account_id);
            // $fbObjImage->{AdImageFields::FILENAME} = base_path() . UPLOADS_FILE_PATH . '/' . $adBody->image;
            // $fbObjImage->create();

            $fbObjCreative = new \FacebookAds\Object\AdCreative(null, 'act_' . $adsets['rightAd']->campaign->account->fb_account_id );

            $fbObjCreative->setData(array(
              AdCreativeFields::NAME => $adBody->name,
              AdCreativeFields::TITLE => $adBody->name,
              AdCreativeFields::BODY => $adBody->description,
              AdCreativeFields::OBJECT_URL => $adBody->link,
              AdCreativeFields::LINK_URL => $adBody->link,
              // AdCreativeFields::IMAGE_HASH => $fbObjImage->hash,
              AdCreativeFields::IMAGE_URL =>  $adBody->image_url,
              // AdCreativeFields::ACTOR_ID =>  'me',
            ));

            $fbObjCreative->create();

            $creatives[] = [
                'creative' => $fbObjCreative->id,
                'name' => $adBody->name,
                'description' => $adBody->description,
                'target_desktop' => $ads->right_ads->show_desktop ? 1 : 0,
                'target_mobile' => 0,
                'type' => Ad::TYPE_RIGHT_HAND_SIDE_AD,
                'url' => $adBody->link,
                'adset' => $adsets['rightAd']
            ];
        }

        foreach($ads->news_ads->ads as $adBody) {
            // $fbObjLinkData = new LinkData();
            // $fbObjLinkData->setData([
            //     LinkDataFields::MESSAGE => $adBody->description,
            //     LinkDataFields::LINK => $adBody->link,
            //     LinkDataFields::CAPTION => $adBody->name
            // ]);
            // $fbObjStorySpec = new ObjectStorySpec();
            // $fbObjStorySpec->setData(array(
            //     ObjectStorySpecFields::PAGE_ID => $adBody->fb_page_id,
            //     ObjectStorySpecFields::LINK_DATA => $fbObjLinkData,
            // ));
            // $fbObjCreative = new \FacebookAds\Object\AdCreative(null, 'act_' . $adsets['newsAd']->campaign->account->fb_account_id );
            // $fbObjCreative->setData(array(
            //     AdCreativeFields::NAME => $adBody->name,
            //     AdCreativeFields::OBJECT_STORY_SPEC => $fbObjStorySpec,
            // ));
            // $fbObjCreative->create();

            $fbObjCreative = new \FacebookAds\Object\AdCreative(null, 'act_' . $adsets['newsAd']->campaign->account->fb_account_id );

            $fbObjCreative->setData(array(
              AdCreativeFields::NAME => $adBody->name,
              AdCreativeFields::TITLE => $adBody->name,
              AdCreativeFields::BODY => $adBody->description,
              AdCreativeFields::OBJECT_URL => $adBody->link,
              AdCreativeFields::LINK_URL => $adBody->link,
              // AdCreativeFields::IMAGE_HASH => $fbObjImage->hash,
              AdCreativeFields::IMAGE_URL =>  $adBody->profile_image_url,
              // AdCreativeFields::ACTOR_ID =>  'me',
            ));

            $fbObjCreative->create();

            $creatives[] = [
                'creative' => $fbObjCreative->id,
                'name' => $adBody->name,
                'description' => $adBody->description,
                'target_desktop' => $ads->news_ads->show_mobile ? 1 : 0,
                'target_mobile' => $ads->news_ads->show_desktop ? 1 : 0,
                'type' => Ad::TYPE_NEWS_FEED_AD,
                'url' => $adBody->link,
                'adset' => $adsets['newsAd']
            ];
        }

        // foreach($ads->multiProductsAd as $adBody) {
        //     $fbObjLinkData = new LinkData();
        //     $fbObjLinkData->setData([
        //         LinkDataFields::MESSAGE => $adBody->description,
        //         LinkDataFields::LINK => $adBody->link,
        //         LinkDataFields::CAPTION => $adBody->name
        //     ]);
        //     $fbObjStorySpec = new ObjectStorySpec();
        //     $fbObjStorySpec->setData(array(
        //         ObjectStorySpecFields::PAGE_ID => $adBody->fb_page_id,
        //         ObjectStorySpecFields::LINK_DATA => $fbObjLinkData,
        //     ));
        //     $fbObjCreative = new \FacebookAds\Object\AdCreative(null, 'act_' . $adsets['multiProductsAd']->campaign->account->fb_account_id );
        //     $fbObjCreative->setData(array(
        //         AdCreativeFields::NAME => $adBody->name,
        //         AdCreativeFields::OBJECT_STORY_SPEC => $fbObjStorySpec,
        //     ));
        //     $fbObjCreative->create();

        //     $creatives[] = [
        //         'creative' => $fbObjCreative->id,
        //         'name' => $adBody->name,
        //         'description' => $adBody->description,
        //         'target_desktop' => $options->newsAdDesktop ? 1 : 0,
        //         'target_mobile' => $options->newsAdMobile ? 1 : 0,
        //         'type' => Ad::TYPE_NEWS_FEED_AD,
        //         'url' => $adBody->link,
        //         'adset' => $adsets['multiProductsAd']
        //     ];
        // }


        foreach($creatives as $creative) {

            $fbObjAd = new \FacebookAds\Object\Ad(null, 'act_' . $creative['adset']->campaign->account->fb_account_id );

            $fbObjAd->setData([
                AdFields::NAME => $creative['name'],
                AdFields::ADSET_ID => $creative['adset']->fb_adset_id,
                AdFields::CREATIVE => ['creative_id' => $creative['creative']]
            ]);

            $fbObjAd->create(array(
                \FacebookAds\Object\Ad::STATUS_PARAM_NAME => \FacebookAds\Object\Ad::STATUS_ACTIVE,
                // 'campaign_group_id' => $creative['adset']->campaign->fb_campaign_id,
                // 'adgroup_status' => 'ACTIVE'
            ));


            $ad = new Ad();
            $ad->ad_set_id = $creative['adset']->id;
            $ad->description = $creative['description'];
            $ad->fb_ad_id = $fbObjAd->id;
            $ad->name = $creative['name'];
            $ad->target_desktop = $creative['target_desktop'];
            $ad->target_mobile = $creative['target_mobile'];
            $ad->type = $creative['type'];
            $ad->url = $creative['url'];
            $ad->save();
        }
    }
}