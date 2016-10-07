<?php

namespace App\Http\Controllers\Google;

use App\Contracts\Repositories\GoogleAccessTokenRepository;
use App\Http\Controllers\APIBaseController;
use App\Support\Google\Auth as GoogleAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class AuthController extends APIBaseController
{
    /**
     * @var GoogleAuth
     */
    private $googleAuth;

    /**
     * The google access token repository implementation.
     *
     * @var GoogleAccessTokenRepository
     */
    private $googleAccessTokenRepository;

    /**
     * Create a new AuthController instance.
     *
     * @param GoogleAuth                  $googleAuth
     * @param GoogleAccessTokenRepository $googleAccessTokenRepository
     */
    public function __construct(
        GoogleAuth $googleAuth,
        GoogleAccessTokenRepository $googleAccessTokenRepository
    ){
        $this->googleAuth = $googleAuth;
        $this->googleAccessTokenRepository = $googleAccessTokenRepository;
    }

    /**
     * Log the user into their Google acount.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        // Check that Google provided a api response code.
        //
        if( Input::has('code') )
        {
            // Log the user into their google account.
            //
            $json = $this->googleAuth->login( Input::get('code') );

            // Store the access token provided by Google.
            //
            $login = $this->googleAccessTokenRepository->createForUserFromJson( Auth::user(), $json );

            // Return successful response.
            //
            return $this->response([
                'status'        => 'successful',
                'access_token'  => $login
            ]);
        }

        // Return failed response.
        //
        return $this->response([
            'status'    => 'failed',
            'login_url' => $this->googleAuth->getLoginurl()
        ]);
    }

    /**
     * Return if the user is currently logged into Google.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginStatus()
    {
        if( $this->googleAuth->isLoggedIn() )
        {
            // Return the response.
            //
            return $this->response([
                'logged_in' => true
            ]);
        }

        // Return the response.
        //
        return $this->response([
            'logged_in' => false,
            'login_url' => $this->googleAuth->getLoginurl()
        ]);
    }

    /**
     * Log the user out of Google.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // Log the user out of Google.
        //
        $logout = $this->googleAccessTokenRepository->logoutUser( Auth::user() );

        // Return the response
        //
        return $this->response( $logout );
    }
}