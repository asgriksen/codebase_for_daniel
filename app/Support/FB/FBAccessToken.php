<?php 

namespace App\Support\FB;

use Facebook\Authentication\AccessToken;
use Facebook\Facebook;

class FBAccessToken
{
    /**
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * @var Facebook
     */
    protected $facebook;

    /**
     * @var \Facebook\Authentication\OAuth2Client
     */
    protected $oAuthClient;

    /**
     * Create new FBAccessToken instance.
     *
     * @param AccessToken $accessToken
     */
    public function __construct( AccessToken $accessToken )
    {
        $this->accessToken = $accessToken;

        $this->facebook = new Facebook([
            'app_id'                => config("facebook.app_id"),
            'app_secret'            => config('facebook.secret'),
            'default_graph_version' => 'v2.3'
        ]);

        $this->oAuthClient = $this->facebook->getOAuth2Client();
    }

    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Extend the access token.
     *
     * @return \Facebook\Authentication\AccessToken
     */
    public function extend()
    {
        $accessTokenInfo = $this->oAuthClient->debugToken( $this->accessToken );

        // Return null if token is invalid.
        //
        if( ! $accessTokenInfo->getIsValid() ) return null;

        return $this->oAuthClient
                    ->getLongLivedAccessToken(
                        $this->accessToken
                    );
    }
}