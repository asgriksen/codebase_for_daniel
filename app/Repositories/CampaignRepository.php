<?php 

namespace App\Repositories;

use App\Account;
use App\Campaign;
use App\Contracts\Repositories\CampaignRepository as CampaignRepositoryContract;
use App\Support\Repository\Traits\Repositories;
use App\Http\Requests;
use App\Http\Requests\StoreAdWizardRequest;
use App\User;

class CampaignRepository implements CampaignRepositoryContract
{
    use Repositories;

    /**
     * Create a new CampaignRepository instance.
     *
     * @param Campaign $campaign
     */
    public function __construct( Campaign $campaign )
    {
        $this->model = $campaign;
    }

    /**
     * Fetch campaign record by account id and facebook campaign id.
     *
     * @param $account_id
     * @param $fb_campaign_id
     *
     * @return mixed
     */
    public function byAccountIdAndFbCampaignId( $account_id, $fb_campaign_id )
    {
        return $this->getModel()
                    ->whereAccountId($account_id)
                    ->whereFbCampaignId( $fb_campaign_id )
                    ->first();
    }

    /**
     * Fetch a campaign record by it facebook campaign id.
     *
     * @param $fb_campaign_id
     *
     * @return mixed
     */
    public function byFbCampaignId( $fb_campaign_id )
    {
        return $this->getModel()->whereFbCampaignId( $fb_campaign_id )->first();
    }

    /**
     * Fetch campaign record by id within the users known campaigns.
     *
     * @param int  $id   The id of the target campaign record.
     * @param User $user The user object.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function byIdAndUser( $id, User $user )
    {
        return $user->campaigns()->find( $id );
    }

    /**
     * Return all user account campaigns.
     *
     * @param int             $id             The campaign id number.
     * @param User            $user           The user object.
     * @param array           $fields         The facebook fields we want returned.
     * @param string          $start_date     The metric start date in format yyyy-mm-dd.
     * @param string          $end_date       The metric end date in format yyyy-mm-dd.
     * @param bool|string|int $time_increment Metric data breakdown.
     *
     * @return array
     */
    public function byIdAndUserWithMetricsByDate( $id, User $user, $fields = [], $start_date, $end_date, $time_increment = false )
    {
        $campaign = $this->byIdAndUser($id, $user);

        // Set the metric fields, start and end dates
        //
        $campaign->setMetricFieldsAttribute($fields);
        $campaign->setMetricStartDateAttribute($start_date);
        $campaign->setMetricEndDateAttribute($end_date);
        $campaign->setMetricTimeIncrementAttribute($time_increment);

        // Include the metrics in the array data.
        //
        $campaign->setAppends(['metrics']);

        // Hide the account record from the model array.
        //
        $campaign->setHidden(['account']);

        // Now return the campaign record.
        //
        return $campaign;
    }

    /**
     * Return all user account campaigns.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function byUser( User $user )
    {
        $selected_account_ids = $user->accounts()->isSelected()->lists('id');

        return $this->getModel()->whereIn('account_id', $selected_account_ids)->get();
    }

    /**
     * Return all user account campaigns.
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
        // Obtain the users selected account ids.
        //
        $selected_account_ids = $user->accounts()->isSelected()->lists('id');

        // Fetch the campaigns for the selected accounts.
        //
        $campaigns = $this->getModel()->whereIn('account_id', $selected_account_ids)->get();

        // Define array that will be later used as the output.
        //
        $output = [];

        // Iterate through the campaigns.
        //
        foreach( $campaigns as $campaign )
        {
            // Set the metric fields, start and end dates
            //
            $campaign->setMetricFieldsAttribute($fields);
            $campaign->setMetricStartDateAttribute($start_date);
            $campaign->setMetricEndDateAttribute($end_date);
            $campaign->setMetricTimeIncrementAttribute($time_increment);

            // Include the metrics in the array data.
            //
            $campaign->setAppends(['metrics']);

            // Hide the account record from the model array.
            //
            $campaign->setHidden(['account']);

            // Append the campaign data to our new output array.
            //
            $output[] = $campaign->toArray();
        }

        return $output;
    }

    /**
     * Import campaign records for a given account.
     *
     * @param array   $campaigns
     * @param Account $account
     *
     * @return bool
     */
    public function importToAccount( $campaigns = [], Account $account )
    {
        foreach( $campaigns as $campaign )
        {
            // Access the campaign data prepared for the eloquent model.
            //
            $data = $campaign->getDataForModel();

            // Attempt to fetch an existing campaign record.
            //
            $campaign_record = $this->byAccountIdAndFbCampaignId($account->id, $data['fb_campaign_id']);

            // If the campaign record already exists then we will proceed to update it.
            //
            if( $campaign_record )
            {
                // Update the campaign record with new data.
                //
                $this->update( $campaign_record, $data );

                // Skip to the next iteration.
                //
                continue;
            }

            //-------------------------------------------------//
            // Otherwise we will create a new campaign record
            // and associate it with the account.
            //-------------------------------------------------//

            // Create a new campaign instance.
            //
            $instance = $this->newInstance( $campaign->getDataForModel() );

            // Save the campaign record and associate it with the account.
            //
            $account->campaigns()->save( $instance );
        }

        return true;
    }

    /**
     * Create a campaign
     *
     * @param StoreAdWizardRequest $request
     * @param Account $account
     *
     * @return FacebookAds\Object\Campaign
     */
    public function create( StoreAdWizardRequest $request, Account $account ) {

        // create campaign object on facebook
        $fbCampaign = new \FacebookAds\Object\Campaign(null, 'act_' . $account->fb_account_id );

        $fbCampaign->setData( $request->campaignFBData() );

        $fbCampaign->create([
            'status' => 'ACTIVE'
        ]);

        // create campaign object on the database
        $campaign = new Campaign();
        $campaign->fill ( $request->campaignData() );
        $campaign->account_id = $account->id;
        $campaign->fb_campaign_id = $fbCampaign->id;
        $campaign->save();

        return $campaign;
    }
}