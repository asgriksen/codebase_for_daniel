<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\AdSetRepository;
use App\Http\Requests;
use App\Http\Requests\StoreAdSetRequest;
use App\Http\Requests\UpdateAdSetRequest;
use Carbon\Carbon;
use Auth;
use FacebookAds\Object\Fields\AdSetFields;use Illuminate\Http\Request;
use Input;

class AdSetController extends APIBaseController
{
    /**
     * The ad set repository implementation.
     *
     * @var AdSetRepository
     */
    protected $adSetRepository;

    /**
     * @param AdSetRepository $adSetRepository
     */
    public function __construct( AdSetRepository $adSetRepository )
    {
        parent::__construct();

        $this->adSetRepository = $adSetRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $fields         = [];
        $user           = Auth::user();
        $start_date     = $this->getStartDate(Input::get('start_date'));
        $end_date       = $this->getEndDate(Input::get('end_date'));
        $time_increment = (bool) Input::get('time_increment', false);

        $ad_sets = $this->adSetRepository->byUserWithMetricsByDate( $user, $fields, $start_date, $end_date, $time_increment );

        // Return the results.
        //
        return $this->response( $ad_sets ?: [] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdSetRequest $request
     *
     * @return Response
     */
    public function store( StoreAdSetRequest $request )
    {
        // Create a new AdSet record.
        //
        $record = $this->adSetRepository->create( $request->all() );

        // Return the response.
        //
        return $this->response( $record );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show( $id )
    {
        $account_id     = Input::get('account_id', 0);
        $fields         = [];
        $user           = Auth::user();
        $start_date     = $this->getStartDate(Input::get('start_date'));
        $end_date       = $this->getEndDate(Input::get('end_date'));
        $time_increment = (bool) Input::get('time_increment', false);

        // Fetch the ad set records from the database.
        //
        $ad_sets = $this->adSetRepository->byIdWithinAccountIdAndUserWithMetricsByDate( $id, $account_id, $user, $fields, $start_date, $end_date, $time_increment );

        // Return the results.
        //
        return $this->response( $ad_sets ?: [] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int               $id
     *
     * @param UpdateAdSetRequest $request
     *
     * @return Response
     */
    public function update( $id, UpdateAdSetRequest $request )
    {
        // Fetch the ad set record.
        $record = $this->adSetRepository->byId( $id );

        // Update the ad set record.
        //
        $this->adSetRepository->update( $record, $request->all() );

        // Return the response.
        //
        return $this->response( $record );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy( $id )
    {
        //
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
