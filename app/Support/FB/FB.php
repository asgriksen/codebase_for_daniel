<?php 

namespace App\Support\FB;

use App\Account;
use Facebook\Authentication\AccessToken;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\AdGroup;
use FacebookAds\Object\AdSet;

class FB
{
    /**
     * Create new FB instance.
     *
     * @param Account $account
     */
    public function __construct( Account $account )
    {
        $this->account = $account;

        $this->initializeApi();
    }

    /**
     * Initialize the Facebook API
     *
     * @return void;
     */
    public function initializeApi()
    {
        Api::init( config('facebook.app_id'), config('facebook.secret'), $this->account->fb_token );
    }

    /**
     * Create a new Facebook Ad Campaign record.
     *
     * @param  array $data
     *
     * @return FBAdCampaign
     */
    public function createAdCampaign( $data = [] )
    {
        $fb_ad_campaign = new Campaign( null, 'act_' . $this->account->fb_account_id );
        $ad_campaign    = new FBAdCampaign( $fb_ad_campaign );

        return $ad_campaign->setFacebookData( $data )->create();
    }

    /**
     * Create a new Facebook Ad Set record.
     *
     * @param array $data
     *
     * @return FBAdSet
     */
    public function createAdSet( $data = [] )
    {
        $fb_ad_set = new AdSet( null, 'act_' . $this->account->fb_account_id );
        $ad_set    = new FBAdSet( $fb_ad_set );

        return $ad_set->setFacebookData( $data )->create();
    }

    /**
     * Return new Facebook Ad Account Instance.
     *
     * @return AdAccount
     */
    public function getAdAccount()
    {
        return new FBAdAccount(
            new AdAccount( 'act_' . $this->account->fb_account_id )
        );
    }

    /**
     * Return an Ad Campaign.
     *
     * @param $campaign_id
     *
     * @return FBAdCampaign
     */
    public function getAdCampaign( $campaign_id )
    {
        return new FBAdCampaign(
            new Campaign( $campaign_id )
        );
    }

    /**
     * Return an ad group.
     *
     * @param $ad_group_id
     *
     * @return FBAdGroup
     */
    public function getAdGroup( $ad_group_id )
    {
        return new FBAdGroup(
            new AdGroup( $ad_group_id )
        );
    }

    /**
     * Return an ad set.
     *
     * @param $ad_set_id
     *
     * @return FBAdSet
     */
    public function getAdSet( $ad_set_id )
    {
        return new FBAdSet(
            new AdSet( $ad_set_id, $this->account->fb_account_id )
        );
    }

    /**
     * Return new Facebook Access Token Instance.
     *
     * @return FBAccessToken
     */
    public function getAccessToken()
    {
        return new FBAccessToken(
            new AccessToken( $this->account->fb_token )
        );
    }

    /**
     * Update existing Facebook Ad Campaign record.
     *
     * @param  array $data
     *
     * @return bool
     */
    public function updateAdCampaign( $id, $data = [] )
    {
        $fb_ad_campaign = new AdCampaign( $id );
        $ad_campaign    = new FBAdCampaign( $fb_ad_campaign );

        return $ad_campaign->setFacebookUpdateData( $data )->update();
    }

    /**
     * Update existing Facebook Ad Set record.
     *
     * @param  array $data
     *
     * @return bool
     */
    public function updateAdSet( $id, $data = [] )
    {
        $fb_ad_set = new AdSet( $id );
        $ad_set    = new FBAdSet( $fb_ad_set );

        return $ad_set->setFacebookUpdateData( $data )->update();
    }

    /**
     * Update existing Facebook Ad Group record.
     *
     * @param  array $data
     *
     * @return bool
     */
    public function updateAdGroup( $id, $data = [] )
    {
        $fb_ad_group = new AdGroup( $id );
        $ad_group    = new FBAdGroup( $fb_ad_group );

        return $ad_group->setFacebookUpdateData( $data )->update();
    }
}