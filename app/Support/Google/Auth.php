<?php

namespace App\Support\Google;

use Illuminate\Support\Facades;

class Auth extends Client
{
    /**
     * Return the Google login url.
     *
     * @return string
     */
    public function getLoginurl()
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Obtain the access token.
     *
     * @return bool|string
     */
    private function getToken()
    {
        if( session()->has( 'token' ) )
        {
            return session()->get('token');
        }

        if( Auth::check() && Auth::user()->google_access_token )
        {
            return Auth::user()->google_access_token->json;
        }

        return false;
    }

    /**
     * Is the user already logged into Google?
     *
     * @return bool|string
     */
    public function isLoggedIn( )
    {
        // Attempt to located a known token.
        $token = $this->getToken();

        // Use token if it exists within the session data or user account.
        if( $token )
        {
            $this->client->setAccessToken( session()->get('token') );

            return true;
        }

        return $this->client->getAccessToken();
    }

    /**
     * Log the user into their google account.
     *
     * @param $code
     *
     * @return string
     */
    public function login( $code )
    {
        $this->client->authenticate( $code );

        $token = $this->client->getAccessToken();

        session()->put('token', $token );

        return $token;
    }
}