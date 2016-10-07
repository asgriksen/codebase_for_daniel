<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\RuleActionRepository;
use App\Contracts\Repositories\RuleConditionRepository;
use App\Contracts\Repositories\RuleRepository;
use App\Http\Requests;
use App\Http\Requests\StoreRuleRequest;
use App\Http\Requests\UpdateRuleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RuleController extends APIBaseController
{
    /**
     * The rule repository implementation.
     *
     * @var RuleRepository
     */
    private $ruleRepository;

    /**
     * @var RuleActionRepository
     */
    private $ruleActionRepository;

    /**
     * @var RuleConditionRepository
     */
    private $ruleConditionRepository;

    /**
     * Create new RuleController instance.
     *
     * @param RuleRepository          $ruleRepository
     * @param RuleActionRepository    $ruleActionRepository
     * @param RuleConditionRepository $ruleConditionRepository
     */
    public function __construct(
        RuleRepository $ruleRepository,
        RuleActionRepository $ruleActionRepository,
        RuleConditionRepository $ruleConditionRepository
    ){
        $this->ruleRepository = $ruleRepository;
        $this->ruleActionRepository = $ruleActionRepository;
        $this->ruleConditionRepository = $ruleConditionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Return all rules for the logged in user.
        //
        $rules = $this->ruleRepository->byUserWithApplications( Auth::user() );

        // Return the response.
        //
        return $this->response( $rules );
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
     * @param  StoreRuleRequest $request
     *
     * @return Response
     */
    public function store( StoreRuleRequest $request )
    {
        // Create a new rule for the the user.
        //
        $rule = $this->ruleRepository->createForUser( Auth::user(), $request->all(), true );

        // Attach the actions to the rule.
        //
        $this->ruleActionRepository->attachToRule( $request->actions, $rule );

        // Attach the conditions to the rule.
        //
        $this->ruleConditionRepository->attachToRule( $request->conditions, $rule );

        // Lazy load applications into the response.
        //
        $this->ruleRepository->load($rule, ['applications', 'applications.layer']);

        // Return the record.
        //
        return $this->response( $rule );
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
        // Fetch a particular rule, by id, within the users list of rules.
        //
        $rule = $this->ruleRepository->byIdAndUserWithApplications( $id, Auth::user() );

        // Return the response.
        //
        return $this->response( $rule );
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
     * @param  UpdateRuleRequest $request
     * @param  int               $id
     *
     * @return Response
     */
    public function update( UpdateRuleRequest $request, $id )
    {
        // Fetch a particular rule, by id, within the users list of rules.
        //
        $rule = $this->ruleRepository->byIdAndUser( $id, Auth::user() );

        // Now update the record.
        //
        $this->ruleRepository->update( $rule, $request->all() );

        // Attach the actions to the rule.
        //
        $this->ruleActionRepository->flushAndReAttachToRule( $request->actions, $rule );

        // Attach the conditions to the rule.
        //
        $this->ruleConditionRepository->flushAndReAttachToRule( $request->conditions, $rule );

        // Lazy load applications into the response.
        //
        $this->ruleRepository->load($rule, ['applications', 'applications.layer']);

        // Return the response.
        //
        return $this->response( $rule );
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
        // Fetch a particular rule, by id, within the users list of rules.
        //
        $rule = $this->ruleRepository->byIdAndUser( $id, Auth::user() );

        // Now delete the record.
        //
        $delete = $this->ruleRepository->delete( $rule );

        // Return the response.
        //
        return $this->response( $delete );
    }
}
