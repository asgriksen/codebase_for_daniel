<?php

namespace App\Support\Google;

class AccessToken extends Client
{
    /**
     * Return the Google access token.
     *
     * @return string
     */
    public function get()
    {
        return $this->client->getAccessToken();
    }

    /**
     * Set the google access token.
     *
     * @param $access_token_json
     *
     * @return $this
     */
    public function set( $access_token_json )
    {
        $this->client->setAccessToken( $access_token_json );

        return $this;
    }

    /**
     * Refresh the token and return the new one.
     *
     * @return string
     */
    public function refresh()
    {
        $this->client->refreshToken(
            $this->client->getRefreshToken()
        );

        return $this;
    }

    /**
     * Revoke the access token.
     *
     * @return bool
     */
    public function revoke()
    {
        return $this->client->revokeToken(
            $this->client->getAccessToken()
        );
    }
}