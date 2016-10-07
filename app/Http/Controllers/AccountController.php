<?php namespace App\Http\Controllers;

use App\Contracts\Repositories\AccountRepository;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use Auth;
use Carbon\Carbon;
use Input;

class AccountController extends APIBaseController
{
    /**
     * The account repository implementation.
     *
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * Create a new controller instance.
     *
     * @param AccountRepository $accountRepository
     */
    public function __construct( AccountRepository $accountRepository )
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * Return a listing of the users accounts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user           = Auth::user();
        $fields         = ['call_to_action_clicks', 'cost_per_total_action', 'spend', 'unique_clicks'];
        $start_date     = $this->getStartDate(Input::get('start_date'));
        $end_date       = $this->getEndDate(Input::get('end_date'));
        $time_increment = (bool) Input::get('time_increment', false);

        // Fetch the users accounts from the database.
        //
        $accounts = $this->accountRepository->byUserWithMetricsByDate( $user, $fields, $start_date, $end_date, $time_increment );

        // Return the response.
        //
        return $this->response( $accounts ?: [] );
    }

    /**
     * Return a specific account record.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get( $id )
    {
        $user           = Auth::user();
        $fields         = ['cost_per_total_action', 'clicks', 'spend'];
        $start_date     = $this->getStartDate(Input::get('start_date'));
        $end_date       = $this->getEndDate(Input::get('end_date'));
        $time_increment = (bool) Input::get('time_increment', false);

        // Fetch the account record from the database.
        //
        $account = $this->accountRepository->byIdAndUserWithMetricsByDate( $id, $user, $fields, $start_date, $end_date, $time_increment );

        // Bail of the account record does not exist.
        //
        if ( ! $account )
        {
            // Otherwise return an error response.
            //
            return $this->setError( [ 'id' => [ 'Invalid account id.' ] ] )->error( null, 404 );
        }

        // Return the record.
        //
        return $this->response( $account ?: 0 );
    }

    /**
     * Create a new Account record.
     *
     * @param StoreAccountRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create( StoreAccountRequest $request )
    {
        $attributes = [
            'user_id'       => Auth::user()->id,
            'fb_account_id' => $request->fb_account_id,
            'fb_token'      => $request->fb_token
        ];

        // Create the account record.
        //
        $account = $this->accountRepository->create( $attributes );

        // Return the response.
        //
        return $this->response( $account );
    }

    /**
     * Update an account record.
     *
     * @param int                  $id
     * @param UpdateAccountRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( $id , UpdateAccountRequest $request )
    {
        // Fetch the account record from the database.
        //
        $account = $this->accountRepository->byIdAndUser( $id, Auth::user());

        // Update the account record with new data.
        //
        $this->accountRepository->update( $account, $request->all());

        // Return a successful response.
        //
        return $this->response( $account );
    }

    /**
     * Delete an account record.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy( $id )
    {
        // Fetch the account record from the database.
        //
        $account = $this->accountRepository->byIdAndUser( $id, Auth::user() );

        // Bail of the account record does not exist.
        //
        if ( ! $account )
        {
            // Otherwise return an error response.
            //
            return $this->setError( [ 'id' => [ 'Invalid account id.' ] ] )->error( null, 401 );
        }

        // Delete the account.
        //
        $this->accountRepository->delete( $account );

        // Return a successful response.
        //
        return $this->response();
    }

    /**
     * Determine if a provide date matches a defined date format.
     *
     * @param $date
     *
     * @return boolean
     */
    private function matchDateFormat( $date )
    {
        return (bool) preg_match('/\d{4}-\d{2}\-\d{2}/', $date);
    }

    /**
     * Return a properly-structured start date.
     *
     * @param $start_date
     *
     * @return string
     */
    private function getStartDate( $start_date )
    {
        return $this->matchDateFormat( $start_date )
                ? $start_date
                : Carbon::now()->subDays(7)->format('Y-m-d');
    }

    /**
     * Return a properly-structured start date.
     *
     * @param $end_date
     *
     * @return string
     */
    private function getEndDate( $end_date )
    {
        return $this->matchDateFormat( $end_date )
                ? $end_date
                : Carbon::now()->format('Y-m-d');
    }
}
