<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\UtmCodeRepository;
use App\Http\Requests\StoreUtmCodeRequest;
use App\Http\Requests\UpdateUtmCodeRequest;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class UtmCodeController extends APIBaseController
{
    /**
     * @var UtmCodeRepository
     */
    private $utmCodeRepository;

    /**
     * Create new UtmCodeController instance.
     *
     * @param UtmCodeRepository $utmCodeRepository
     */
    public function __construct( UtmCodeRepository $utmCodeRepository )
    {
        $this->utmCodeRepository = $utmCodeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Fetch all utm codes for the user.
        $utm_codes = $this->utmCodeRepository->byUser( Auth::user() );

        // Return the record.
        return $this->response( $utm_codes );
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
     * @param  StoreUtmCodeRequest $request
     *
     * @return Response
     */
    public function store( StoreUtmCodeRequest $request )
    {
        // Create a new utm record and associate it with the user.
        $utm_code = $this->utmCodeRepository->createForUser( Auth::user(), $request->all() );

        // Return the results.
        return $this->response( $utm_code );
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
        // Fetch the utm code from the database.
        $utm_code = $this->utmCodeRepository->byIdAndUser( $id, Auth::user() );

        // Return the response.
        return $this->response($utm_code);
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
     * @param  UpdateUtmCodeRequest $request
     * @param  int                  $id
     *
     * @return Response
     */
    public function update( UpdateUtmCodeRequest $request, $id )
    {
        // Fetch the utm code from the database.
        $utm_code = $this->utmCodeRepository->byIdAndUser( $id, Auth::user() );

        // Update the record.
        $this->utmCodeRepository->update( $utm_code, $request->all() );

        // Return the response.
        return $this->response( $utm_code );
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
        // Fetch the utm code from the database.
        $utm_code = $this->utmCodeRepository->byIdAndUser( $id, Auth::user() );

        // Attempt to delete the record.
        $delete = $this->utmCodeRepository->delete( $utm_code );

        // Return the response.
        return $this->response( $delete );
    }
}
