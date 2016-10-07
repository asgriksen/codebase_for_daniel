<?php

namespace App\Support\Etsy;

use OAuthException;

class Request extends Api
{
    /**
     * @var OAuth
     */
    protected $oauth;

    /**
     * Create new Request instance.
     *
     * @param $access_token
     * @param $access_token_secret
     */
    public function __construct( $access_token, $access_token_secret )
    {
        $this->oauth = $this->initiateOAuth( $access_token, $access_token_secret );
    }

    /**
     * Initiate new OAuth instance.
     *
     * @param $access_token
     * @param $access_token_secret
     *
     * @return mixed
     */
    public function initiateOAuth( $access_token, $access_token_secret )
    {
        // Create a new OAuth instance.
        $oauth = $this->newOAuth( true );

        // Set the tokens.
        $oauth->setToken( $access_token, $access_token_secret );

        // Return the OAuth instance.
        return $oauth;
    }

    /**
     * Make Etsy API request.
     *
     * @param $uri
     * @param $method
     *
     * @return array|mixed
     */
    public function make( $uri, $method )
    {
        $url = $this->getBaseUrl();
        $url .= $uri;

        try
        {
            $this->oauth->fetch($url, null, $this->method( $method ));
        }
        catch ( OAuthException $e )
        {
            return [$e->getMessage()];
        }

        return json_decode($this->oauth->getLastResponse(), true);
    }

    /**
     * Return the appropriate OAuth method constant.
     *
     * @param $name
     *
     * @return mixed
     */
    public function method( $name )
    {
        $name = strtoupper($name);

        return constant("OAUTH_HTTP_METHOD_$name");
    }
}