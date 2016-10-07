<?php

namespace App\Support\Google;

use File;
use Google_Client;

class Client
{
    /**
     * @var Google_Client
     */
    protected $client;

    /**
     * The authentication scopes we want to work within.
     *
     * @var array
     */
    protected $scopes = [
        'https://www.googleapis.com/auth/analytics',
        'https://www.googleapis.com/auth/analytics.manage.users',
        'https://www.googleapis.com/auth/analytics.readonly'
    ];

    /**
     * Create a new Client instance.
     */
    public function __construct()
    {
        $this->client = new Google_Client;

        // Initialize the Google API connection.
        $this->initialize();
    }

    /**
     * Initialize the Api
     */
    public function initialize()
    {
        $config = $this->readConfigFile()->web;

        $this->client->setClientId( $config->client_id );
        $this->client->setClientSecret( $config->client_secret );
        $this->client->setDeveloperKey( env('GOOGLE_API_KEY'));
        $this->client->setRedirectUri( $config->redirect_uris[0] );
        $this->client->setIncludeGrantedScopes(true);
        $this->client->setAccessType("offline");
        $this->client->setApprovalPrompt('force');
        $this->client->setScopes( $this->scopes );
    }

    /**
     * Return the configuration object.
     *
     * @return stdClass
     */
    public function readConfigFile()
    {
        // Path to the file.
        $file_path = app()->environment() === 'production'
                        ? base_path('google.json')
                        : base_path('google.local.json');

        // Read the file.
        $file = File::get($file_path);

        // Decode the data within the file.
        return json_decode( $file );
    }
}