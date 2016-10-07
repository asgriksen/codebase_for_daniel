<?php

namespace App\Support\Google;

use Google_Service_Analytics;

class Analytic extends Client
{
    /**
     * Create a new Google Analytic instance.
     *
     * @param string $access_token
     */
    public function __construct( $access_token = '' )
    {
        // Call the parent constructor.
        parent::__construct();

        // $access_token = '{"access_token":"ya29.2wFsY_wvke_v6JZWScVEn-54oWq9j9et2k8kA66w65g2TPLT_3bRPm8XCtaZZnO_TunqcQ","token_type":"Bearer","expires_in":3599,"created":1440602031}';

        $this->setGoogleAccessToken( $access_token );
    }

    /**
     * Return the Google Analytics account.
     *
     * @return Google_Service_Analytics
     */
    public function analytics()
    {
        return new Google_Service_Analytics( $this->client );
    }

    public function accounts()
    {
        return $this->analytics()->management_accounts->listManagementAccounts();
    }
}