<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\NotificationRepository;
use App\Http\Requests;
use Illuminate\Http\Request;

class NotificationController extends APIBaseController
{
    /**
     * The notification repository implementation.
     *
     * @var NotificationRepository
     */
    private $notificationRepository;

    /**
     * Create new NotificationController instance.
     *
     * @param NotificationRepository $notificationRepository
     */
    public function __construct( NotificationRepository $notificationRepository )
    {
        $this->notificationRepository = $notificationRepository;
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
