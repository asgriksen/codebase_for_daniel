<?php 

namespace App\Repositories;

use App\Account;
use App\Contracts\Repositories\AccountRepository as AccountRepositoryContract;
use App\Support\Repository\Traits\Repositories;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AccountRepository implements AccountRepositoryContract
{
    use Repositories;

    /**
     * The account model.
     *
     * @var Account
     */
    protected $model;

    /**
     * Create new AccountRepository instance.
     *
     * @param Account $account
     */
    public function __construct( Account $account )
    {
        $this->model = $account;
    }

    /**
     * Fetch record by it's id and user.
     *
     * @param int  $id
     * @param User $user
     *
     * @return mixed
     */
    public function byIdAndUser( $id, User $user )
    {
        return $user->accounts()->find( $id );
    }

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
    public function byIdAndUserWithMetricsByDate( $id, User $user, $fields = [], $start_date, $end_date, $time_increment = false )
    {
        $account = $this->getModel()->whereId($id)->whereUserId($user->id)->first();

        if( ! $account ) return null;

        // Set the metric fields, start and end dates
        //
        $account->setMetricFieldsAttribute($fields);
        $account->setMetricStartDateAttribute($start_date);
        $account->setMetricEndDateAttribute($end_date);
        $account->setMetricTimeIncrementAttribute($time_increment);

        // Include the metrics in the array data.
        //
        $account->setAppends(['metrics', 'cpa']);

        // Hide the account record from the model array.
        //
        $account->setHidden(['account']);

        // Now return the account record.
        //
        return $account;
    }

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
    public function byUserWithMetricsByDate( User $user, $fields = [], $start_date, $end_date, $time_increment = false )
    {
        // Fetch all of the user ad accounts from the database.
        $accounts = $this->getModel()->whereUserId($user->id)->get();

        // Data will be injected into this array which will later be returned.
        $output = [];

        foreach( $accounts as $account )
        {
            // Set the metric fields, start and end dates
            //
            $account->setMetricFieldsAttribute($fields);
            $account->setMetricStartDateAttribute($start_date);
            $account->setMetricEndDateAttribute($end_date);
            $account->setMetricTimeIncrementAttribute($time_increment);

            // Include the metrics in the array data.
            //
            $account->setAppends(['metrics', 'cpa']);

            // Hide the account record from the model array.
            //
            $account->setHidden(['account']);

            // Now return the account record.
            //
            $output[] = $account->toArray();
        }

        // Return the output.
        //
        return $output;
    }

    /**
     * Return accounts by user.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function byUser( User $user )
    {
        return $user->accounts;
    }

    /**
     * Create a new account for user.
     *
     * @param User  $user
     * @param array $attributes
     *
     * @return mixed
     */
    public function createForUser( User $user, $attributes = [] )
    {
        return $user->accounts()->save( $this->newInstance($attributes) );
    }
}