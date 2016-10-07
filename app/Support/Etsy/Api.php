<?php

namespace App\Support\Etsy;

use OAuth;

abstract class Api
{
    /**
     * Return the Etsy base API URL.
     *
     * @var string
     */
    protected $base_url = 'https://openapi.etsy.com/v2/';

    /**
     * Return the Etsy base API URL.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * Return a new OAuth instance.
     *
     * @param bool|FALSE $with_uri
     *
     * @return OAuth
     */
    public function newOAuth( $with_uri = false )
    {
        if( $with_uri )
        {
            return new OAuth(
                config('services.etsy.consumer_key'),
                config('services.etsy.consumer_secret'),
                OAUTH_SIG_METHOD_HMACSHA1,
                OAUTH_AUTH_TYPE_URI
            );
        }


        return new OAuth(
            config('services.etsy.consumer_key'),
            config('services.etsy.consumer_secret')
        );
    }
}