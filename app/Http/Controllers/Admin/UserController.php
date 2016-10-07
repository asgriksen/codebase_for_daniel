<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\UserRepository;
use App\Http\Controllers\APIBaseController;
use App\Http\Requests;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Input;

class UserController extends APIBaseController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create new UserController instance.
     *
     * @param UserRepository $userRepository
     */
    public function __construct( UserRepository $userRepository )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Fetch the user from the database in paginate form.
        //
        $users = $this->userRepository->paginate( Input::get('per_page', 15) );

        // Return a successful response with data.
        //
        return $this->response( $users );
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
     * @param StoreUserRequest $request
     *
     * @return Response
     */
    public function store( StoreUserRequest $request )
    {
        // Create a new user record.
        //
        $user = $this->userRepository->create( $request->only('email', 'password') );

        // Return a successful response.
        //
        return $this->response( $user );
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
     * @param UpdateUserRequest $request
     * @param int               $id
     *
     * @return Response
     */
    public function update( UpdateUserRequest $request, $id )
    {
        // Fetch the user record from the database.
        //
        $user = $this->userRepository->byId( $id );

        // Update the record.
        //
        $this->userRepository->update($user, $request->all() );

        // Return a successful response.
        //
        return $this->response();
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
        // Fetch the user record from the database.
        //
        $user = $this->userRepository->byId( $id );

        // Delete the user record.
        //
        $this->userRepository->delete( $user );

        // Return a successful response.
        //
        return $this->response();
    }
}
