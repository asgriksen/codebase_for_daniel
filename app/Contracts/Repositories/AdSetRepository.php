<?php 

namespace App\Contracts\Repositories;

use App\User;

interface AdSetRepository
{
    /**
     * Fetch ad set by its facebook ad set id number.
     *
     * @param int $fb_adset_id
     *
     * @return AdSet|null
     */
    public function byFbAdsetId( $fb_adset_id );

    /**
     * Fetch ad sets from a particular account belonging to a particular user.
     *
     * @param int  $id
     * @param User $user
     * @param bool $return_array
     *
     * @return null
     */
    public function byAccountIdAndUser( $id, User $user, $return_array = true );

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
    public function byAccountIdAndUserWithMetricsByDate( $account_id, User $user, $fields = [], $start_date, $end_date, $time_increment );

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
    public function byIdWithinAccountIdAndUser( $id, $account_id, User $user );

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
    public function byIdWithinAccountIdAndUserWithMetricsByDate( $id, $account_id, User $user, $fields = [], $start_date, $end_date, $time_increment );

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
    public function byUserWithMetricsByDate( User $user, $fields = [], $start_date, $end_date, $time_increment = false );

    /**
     * Import ad set records.
     *
     * @param array $ad_sets
     *
     * @return bool
     */
    public function import( $ad_sets = [ ] );
}