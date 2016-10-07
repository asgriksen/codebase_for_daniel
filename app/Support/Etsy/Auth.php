<?php

namespace App\Support\Etsy;

use Illuminate\Support\Facades\Input;
use OAuthException;

class Auth extends Api
{
    /**
     * The access token URI.
     *
     * @var string
     */
    protected $access_token_uri = 'oauth/access_token';

    /**
     * OAuth instance.
     *
     * @var \OAuth
     */
    protected $oauth;

    /**
     * List of permission scopes.
     *
     * @var array
     */
    protected $permission_scopes = [
        'email_r',
        'listings_w'
    ];

    /**
     * The request token URI.
     *
     * @var string
     */
    protected $request_token_uri = 'oauth/request_token';

    /**
     * Create new Auth instance.
     */
    public function __construct()
    {
        $this->oauth = $this->newOAuth();
    }

    /**
     * Return the access token uri.
     *
     * @return string
     */
    public function getAccessTokenUri()
    {
        return $this->access_token_uri;
    }

    /**
     * Return the access token uri.
     *
     * @return string
     */
    public function getAccessTokenUrl()
    {
        return $this->getBaseUrl() . $this->getAccessTokenUri();
    }

    /**
     * Return the Etsy login url.
     *
     * @return array
     */
    public function getLoginUrl()
    {
        $request_token = $this->oauth->getRequestToken(
            $this->getRequestTokenUrl(),
            route('api.etsy.auth.login')
        );

        // Push the oauth token secret into the session.
        session()->put('request_secret', $request_token['oauth_token_secret']);

        // Return the login url.
        return $request_token['login_url'];
    }

    /**
     * Return list of permission scopes.
     *
     * @return array
     */
    public function getPermissionScopes()
    {
        return $this->permission_scopes;
    }

    /**
     * Return list of permission scopes.
     *
     * @return array
     */
    public function getPermissionScopesAsString()
    {
        return implode('%20', $this->getPermissionScopes());
    }

    /**
     * Return the request token uri.
     *
     * @return string
     */
    public function getRequestTokenUri()
    {
        return $this->request_token_uri;
    }

    /**
     * Return full request token URL.
     *
     * @return string
     */
    public function getRequestTokenUrl()
    {
        $parts = [
            $this->getBaseUrl(),
            $this->getRequestTokenUri(),
            '?',
            'scope',
            '=',
            $this->getPermissionScopesAsString()
        ];

        return implode('', $parts);
    }

    /**
     * Do we have temporary credentials in the query string from Etsy?
     *
     * @return bool
     */
    public function hasTemporaryCredentials()
    {
        return Input::has([
            'oauth_token',
            'oauth_verifier'
        ]);
    }

    /**
     * @return array
     */
    public function obtainTokenCredentials()
    {
        $request_token        = Input::get('oauth_token');
        $request_token_secret = session()->get('request_secret');
        $verifier             = Input::get('oauth_verifier');

        // set the temporary credentials and secret
        $this->oauth->setToken($request_token, $request_token_secret);

        try {
            $acc_token = $this->oauth->getAccessToken($this->getAccessTokenUrl(), null, $verifier);
        }
        catch (OAuthException $e)
        {
            return [$e->getMessage()];
        }

        session()->forget('request_secret');

        $response = $acc_token;
        $response['oauth_verifier'] = $verifier;

        return $response;
    }
}