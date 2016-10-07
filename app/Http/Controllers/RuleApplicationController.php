<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\RuleApplicationRepository;
use App\Http\Requests;
use App\Http\Requests\StoreRuleApplicationRequest;
use App\Http\Requests\UpdateRuleApplicationRequest;
use Illuminate\Support\Facades\Auth;

class RuleApplicationController extends APIBaseController
{
    /**
     * The rule application repository implementation.
     *
     * @var RuleApplicationRepository
     */
    private $ruleApplicationRepository;

    /**
     * Create new RuleApplicationController instance.
     *
     * @param RuleApplicationRepository $ruleApplicationRepository
     */
    public function __construct( RuleApplicationRepository $ruleApplicationRepository )
    {
        $this->ruleApplicationRepository = $ruleApplicationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Return all rule applications for the logged in user.
        //
        $ruleApplications = $this->ruleApplicationRepository->byUser( Auth::user() );

        // Return the response.
        //
        return $this->response( $ruleApplications );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRuleApplicationRequest $request
     *
     * @return Response
     */
    public function store( StoreRuleApplicationRequest $request )
    {
        // Create a new rule application for the the user.
        //
        $ruleApplication = $this->ruleApplicationRepository->create( $request->all() );

        // Return the record.
        //
        return $this->response( $ruleApplication );
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
        // Fetch a particular rule application, by id, within the users list of rule applications.
        //
        $ruleApplication = $this->ruleApplicationRepository->byIdAndUser( $id, Auth::user() );

        // Return the response.
        //
        return $this->response( $ruleApplication );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit( $id )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRuleApplicationRequest $request
     * @param  int               $id
     *
     * @return Response
     */
    public function update( UpdateRuleApplicationRequest $request, $id )
    {
        // Fetch a particular rule application, by id, within the users list of rule applications.
        //
        $ruleApplication = $this->ruleApplicationRepository->byIdAndUser( $id, Auth::user() );

        // Now update the record.
        //
        $this->ruleApplicationRepository->update( $ruleApplication, $request->all() );

        // Return the response.
        //
        return $this->response( $ruleApplication );
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
        // Fetch a particular rule application, by id, within the users list of rule application.
        //
        $ruleApplication = $this->ruleApplicationRepository->byIdAndUser( $id, Auth::user() );

        // Now delete the record.
        //
        $delete = $this->ruleApplicationRepository->delete( $ruleApplication );

        // Return the response.
        //
        return $this->response( $delete );
    }
}
