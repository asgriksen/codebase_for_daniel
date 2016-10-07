<?php

namespace App\Http\Controllers\Google;

use App\Contracts\Repositories\GoogleAnalyticAccountRepository;
use App\Http\Controllers\APIBaseController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AccountController extends APIBaseController
{
    /**
     * The google analytic account repository implementation.
     *
     * @var GoogleAnalyticAccountRepository
     */
    private $googleAnalyticAccountRepository;

    /**
     * Create new AccountController instance.
     *
     * @param GoogleAnalyticAccountRepository $googleAnalyticAccountRepository
     */
    public function __construct( GoogleAnalyticAccountRepository $googleAnalyticAccountRepository )
    {
        $this->googleAnalyticAccountRepository = $googleAnalyticAccountRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Fetch the users google analytic account records form the database.
        //
        $accounts = $this->googleAnalyticAccountRepository->byUser( Auth::user() );

        // Return the results.
        //
        return $this->response( $accounts ?: [] );
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
