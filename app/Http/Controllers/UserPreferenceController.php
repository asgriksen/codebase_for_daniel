<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\UserPreferenceRepository;
use App\Http\Requests;
use App\Http\Requests\StoreUserPreferenceRequest;
use App\Http\Requests\UpdateUserPreferenceRequest;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends APIBaseController
{
    /**
     * @var UserPreferenceRepository
     */
    private $userPreferenceRepository;

    /**
     * Create new UserPreferenceController instance.
     *
     * @param UserPreferenceRepository $userPreferenceRepository
     */
    public function __construct( UserPreferenceRepository $userPreferenceRepository )
    {
        $this->userPreferenceRepository = $userPreferenceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Fetch all preferences for the logged-in user.
        //
        $preferences = $this->userPreferenceRepository->byUser( Auth::user() );

        // Return the response.
        //
        return $this->response( $preferences );
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
     * @param StoreUserPreferenceRequest $request
     *
     * @return Response
     */
    public function store( StoreUserPreferenceRequest $request )
    {
        // Define the data we want inserted into the record.
        //
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        // Store the new user preference record.
        //
        $preference = $this->userPreferenceRepository->create( $data );

        // Now return the response.
        //
        return $this->response( $preference );
    }

    /**
     * Display the specified resource.
     *
     * @param  string $key
     *
     * @return Response
     */
    public function show( $key )
    {
        // Fetch the record from the database.
        //
        $preference = $this->userPreferenceRepository->byUserAndKey( Auth::user(), $key );

        // Return the response.
        //
        return $this->response( $preference );
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
     * @param  UpdateUserPreferenceRequest $request
     * @param  string                      $key
     *
     * @return Response
     */
    public function update( UpdateUserPreferenceRequest $request, $key )
    {
        // Fetch the record from the database.
        //
        $preference = $this->userPreferenceRepository->byUserAndKey( Auth::user(), $key );

        // Now update the record with new data.
        //
        $this->userPreferenceRepository->update( $preference, $request->all() );

        // Now return the response.
        //
        return $this->response( $preference );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $key
     *
     * @return Response
     */
    public function destroy( $key )
    {
        // Fetch the record from the database.
        //
        $preference = $this->userPreferenceRepository->byUserAndKey( Auth::user(), $key );

        // Now delete the record.
        //
        $delete = $this->userPreferenceRepository->delete( $preference );

        // Return the response.
        //
        return $this->response( $delete );
    }
}
