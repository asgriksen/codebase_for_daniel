<?php namespace App\Http\Controllers;

use App\Contracts\Repositories\CampaignRepository;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use Auth;
use Carbon\Carbon;
use Input;
use FacebookAds\Object\AdCampaign;
use Validator;

class CampaignController extends APIBaseController {

    /**
     * @var CampaignRepository
     */
    private $campaignRepository;

    /**
     * @param CampaignRepository $campaignRepository
     */
    public function __construct( CampaignRepository $campaignRepository )
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * Return all campaigns with metric data for the users selected accounts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user           = Auth::user();
        $start_date     = $this->getStartDate(Input::get('start_date'));
        $end_date       = $this->getEndDate(Input::get('end_date'));
        $time_increment = (bool) Input::get('time_increment', false);

        $data = $this->campaignRepository->byUserWithMetricsByDate($user, [], $start_date, $end_date, $time_increment );

        return $this->response( $data ?: [] );
    }

    /**
     * Return an individual campaign record.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $fields         = [];
        $start_date     = $this->getStartDate(Input::get('start_date'));
        $end_date       = $this->getEndDate(Input::get('end_date'));
        $time_increment = (bool) Input::get('time_increment', false);

        // Fetch the campaign record from the database.
        //
        $campaign = $this->campaignRepository->byIdAndUserWithMetricsByDate($id, Auth::user(), $fields, $start_date, $end_date, $time_increment);

        if( ! $campaign )
        {
            return $this->setError(['id' => ['Invalid campaign id.']])->error( null, 401 );
        }

        // Return the response.
        //
        return $this->response( $campaign ?: [] );
    }

    /**
     * Create a new campaign record.
     *
     * @param StoreCampaignRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create( StoreCampaignRequest $request )
    {
        // Create a new campaign record.
        //
        $campaign = $this->campaignRepository->create( $request->all() );

        // Return a successful response.
        //
        return $this->response( $campaign );
    }

    /**
     * Update a campaign record with new data.
     *
     * @param int                   $id
     * @param UpdateCampaignRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( $id , UpdateCampaignRequest $request )
    {
        // Fetch the campaign record from the database.
        //
        $campaign = $this->campaignRepository->byIdAndUser($id, Auth::user());

        // Update the campaign record.
        //
        $this->campaignRepository->update($campaign, $request->all());

        // Return response.
        //
        return $this->response( $campaign );
    }

    /**
     * Delete a particular campaign record.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy( $id )
    {
        // Fetch the campaign record from the database.
        //
        $campaign = $this->campaignRepository->byIdAndUser($id, Auth::user());

        // Bail if the record does not exist.
        //
        if( ! $campaign )
        {
            return $this->setError(['id' => ['Invalid campaign id.']])->error(null, 404);
        }

        // Attempt to delete the record.
        //
        $delete = $this->campaignRepository->delete( $campaign );

        // Unable to delete the record.
        //
        if( ! $delete )
        {
            return $this->setError(['id' => ['Unable to delete record.']])->error(null, 401);
        }

        // Otherwise return that everything was successful.
        //
        return $this->response( $delete );
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
