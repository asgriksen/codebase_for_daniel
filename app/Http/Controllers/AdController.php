<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\AdRepository;
use App\Http\Requests;
use App\Http\Requests\StoreAdRequest;
use App\Http\Requests\UpdateAdRequest;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Input;

class AdController extends APIBaseController
{
    /**
     * @var AdRepository
     */
    private $adRepository;

    /**
     * Create new AdController instance.
     *
     * @param AdRepository $adRepository
     */
    public function __construct( AdRepository $adRepository )
    {
        $this->adRepository = $adRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $account_id     = Input::get('account_id', false);
        $user           = Auth::user();
        $fields         = Input::get('fields', []);
        $start_date     = $this->getStartDate(Input::get('start_date'));
        $end_date       = $this->getEndDate(Input::get('end_date'));
        $time_increment = (bool) Input::get('time_increment', false);

        $data = $account_id
              ? $this->adRepository->byAccountIdAndUserWithMetricsByDate( $account_id, $user, $fields, $start_date, $end_date, $time_increment )
              : $this->adRepository->byUserWithMetricsByDate( $user, $fields, $start_date, $end_date, $time_increment );

        return $this->response( $data ?: [] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreAdRequest $request
     *
     * @return Response
     */
    public function store( StoreAdRequest $request )
    {
        //
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
        $ad_set_id      = Input::get('ad_set_id', 0);
        $user           = Auth::user();
        $account_id     = Input::get('account_id', 0);
        $fields         = Input::get('fields', []);
        $start_date     = $this->getStartDate(Input::get('start_date'));
        $end_date       = $this->getEndDate(Input::get('end_date'));
        $time_increment = (bool) Input::get('time_increment', false);

        $data = $this->adRepository
            ->byIdAndAdSetIdWithinUserAccountIdWithMetricsByDate(
                $id,
                $ad_set_id,
                $user,
                $account_id,
                $fields,
                $start_date,
                $end_date,
                $time_increment
            );

        return $this->response( $data ?: [] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdRequest $request
     * @param  int            $id
     *
     * @return Response
     */
    public function update( UpdateAdRequest $request, $id )
    {
        //
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
