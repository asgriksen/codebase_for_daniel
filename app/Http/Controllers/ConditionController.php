<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\ConditionRepository;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConditionRequest;
use App\Http\Requests\UpdateConditionRequest;
use Illuminate\Http\Request;

class ConditionController extends APIBaseController
{
    /**
     * @var ConditionRepository
     */
    private $conditionRepository;

    /**
     * Create new ConditionController instance.
     *
     * @param ConditionRepository $conditionRepository
     */
    public function __construct( ConditionRepository $conditionRepository )
    {
        $this->conditionRepository = $conditionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Fetch all conditions from the database.
        //
        $conditions = $this->conditionRepository->all();

        // Return the response.
        //
        return $this->response( $conditions );
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
     * @param  StoreConditionRequest $request
     *
     * @return Response
     */
    public function store( StoreConditionRequest $request )
    {
        // Create a new condition record.
        //
        $condition = $this->conditionRepository->create( $request->all() );

        // Return the response.
        //
        return $this->response( $condition );
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
        // Fetch the condition record from the database.
        //
        $condition = $this->conditionRepository->byId( $id );

        // Return the response.
        //
        return $this->response( $condition );
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
     * @param  UpdateConditionRequest $request
     * @param  int                    $id
     *
     * @return Response
     */
    public function update( UpdateConditionRequest $request, $id )
    {
        // Fetch the condition record from the database.
        //
        $condition = $this->conditionRepository->byId( $id );

        // Update the condition record.
        //
        $this->conditionRepository->update( $condition, $request->all() );

        // Return the response.
        //
        return $this->response( $condition );
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
        // Fetch the condition record from the database.
        //
        $condition = $this->conditionRepository->byId( $id );

        // Delete the condition record.
        //
        $delete = $this->conditionRepository->delete( $condition );

        // Return the response.
        //
        return $this->response( $delete );
    }
}
