<?php 

namespace App\Contracts\Repositories;

use App\User;

interface AccountRepository
{
    /**
     * Fetch record by it's id and user.
     *
     * @param int  $id
     * @param User $user
     *
     * @return mixed
     */
    public function byIdAndUser( $id, User $user );

    /**
     * Return accounts by user.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function byUser( User $user );

    /**
     * Return all user ad accounts with metrics.
     *
     * @param int             $id             The id of the account.
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
     * Return all user ad accounts with metrics.
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
     * Create a new account for user.
     *
     * @param User  $user
     * @param array $attributes
     *
     * @return mixed
     */
    public function createForUser( User $user, $attributes = [] );
}