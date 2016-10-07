<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\AdCreativeRepository;
use App\Http\Requests;
use Illuminate\Http\Request;

class AdCreativeController extends APIBaseController
{
    /**
     * The ad creative repository implementation.
     *
     * @var AdCreativeRepository
     */
    private $adCreativeRepository;

    /**
     * Create new NotificationController instance.
     *
     * @param AdCreativeRepository $adCreativeRepository
     */
    public function __construct( AdCreativeRepository $adCreativeRepository )
    {
        $this->adCreativeRepository = $adCreativeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
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
     * @param  Request $request
     *
     * @return Response
     */
    public function store( Request $request )
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
        //
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
     * @param  Request $request
     * @param  int     $id
     *
     * @return Response
     */
    public function update( Request $request, $id )
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
}
