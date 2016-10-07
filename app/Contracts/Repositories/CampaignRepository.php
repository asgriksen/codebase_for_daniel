<?php 

namespace App\Contracts\Repositories;

use App\Account;
use App\User;

interface CampaignRepository
{
    /**
     * Fetch campaign record by account id and facebook campaign id.
     *
     * @param $account_id
     * @param $fb_campaign_id
     *
     * @return mixed
     */
    public function byAccountIdAndFbCampaignId( $account_id, $fb_campaign_id );

    /**
     * Fetch a campaign record by it facebook campaign id.
     *
     * @param $fb_campaign_id
     *
     * @return mixed
     */
    public function byFbCampaignId( $fb_campaign_id );

    /**
     * Fetch campaign record by id within the users known campaigns.
     *
     * @param int  $id   The id of the target campaign record.
     * @param User $user The user object.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function byIdAndUser( $id, User $user );

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
    public function byIdAndUserWithMetricsByDate( $id, User $user, $fields = [], $start_date, $end_date, $time_increment = false );

    /**
     * Return all user account campaigns.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function byUser( User $user );

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
    public function byUserWithMetricsByDate( User $user, $fields = [], $start_date, $end_date, $time_increment = false );

    /**
     * Import campaign records for a given account.
     *
     * @param array   $campaigns
     * @param Account $account
     *
     * @return bool
     */
    public function importToAccount( $campaigns = [], Account $account );
}