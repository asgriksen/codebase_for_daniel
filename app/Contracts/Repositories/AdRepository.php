<?php 

namespace App\Contracts\Repositories;

use App\User;

interface AdRepository
{
    /**
     * Fetch ad records by their ad set ID number within the users selected account ad sets.
     *
     * @param int  $ad_set_id  The id number of the ad set record.
     * @param User $user       The user object.
     * @param int  $account_id The id number of the selected account.
     *
     * @return Ad|null
     */
    public function byAdSetIdWithinUserAccountId( $ad_set_id, User $user, $account_id );

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
    public function byAdSetIdWithinUserAccountIdWithMetricsByDate( $ad_set_id, User $user, $account_id, $fields = [ ], $start_date, $end_date, $time_increment = false );
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
    public function byIdAndAdSetIdWithinUserAccountId( $id, $ad_set_id, User $user, $account_id );

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
    public function byIdAndAdSetIdWithinUserAccountIdWithMetricsByDate( $id, $ad_set_id, User $user, $account_id, $fields = [ ], $start_date, $end_date, $time_increment = false );

    /**
     * Import ads from facebook ad sets.
     *
     * @param array $ad_sets
     *
     * @return bool
     */
    public function importFromAdSets( $ad_sets = [] );
}