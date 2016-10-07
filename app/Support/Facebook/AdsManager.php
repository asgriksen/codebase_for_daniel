<?php

namespace App\Support\Facebook;

use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Fields\AdGroupFields;

use App\Campaign;
use App\AdSet;
use App\Ad;

class AdsManager {

	public static $current_fb_token = null;

	public static function init( $fb_token )
    {
		if( AdsManager::$current_fb_token != $fb_token )
        {
			// Initialize a new session and instantiate an Api object
			Api::init(config("facebook.app_id"), config('facebook.secret'), $fb_token);
		}
	}

	// Get account name of the specified account_id
	public static function getAccountName($fb_account_id)
    {
		try {
			// Read account
			$account = new AdAccount('act_' . $fb_account_id);
			$fields = [
                AdAccountFields::NAME
			];

			$account->read($fields);
			$fb_account_name = $account->{AdAccountFields::NAME};

			return $fb_account_name;
		}
		catch(Exception $e) {
			return null;
		}
	}

	public static function scrapeObjects($account)
    {
    	// FB account
    	$account->name = AdsManager::getAccountName($account->fb_account_id);
    	$account->save();
    	
		$fbAccount = new AdAccount('act_'. $account->fb_account_id);

		/* 
		 * campaigns
		 */
		$fbCampaigns = AdsManager::getCampaigns($fbAccount);

        foreach($fbCampaigns as $fbCampaign)
        {

        	// find the existing campaign in the database
            $campaign = Campaign::where('account_id', '=', $account->id)
            						->where('fb_campaign_id', '=', $fbCampaign->{CampaignFields::ID})
                                    ->first();


            // create new one if not exists
            if($campaign == null)
            {
                $campaign = new Campaign;
                $campaign->account_id = $account->id;
                $campaign->fb_campaign_id = $fbCampaign->{CampaignFields::ID};
                $campaign->name = $fbCampaign->{CampaignFields::NAME};
            }

            // update the campaign status
            $campaign->status = $fbCampaign->{CampaignFields::STATUS};

            // save
            $campaign->save();
        }

        /*
         * ad sets
		 */

        // scraps all ad sets under the specific fb ad account
        $fbAdSets = AdsManager::getAdSets($fbAccount);

        foreach($fbAdSets as $fbAdSet) {

        	// find the existing campaign - MUST EXIST
        	$campaign = Campaign::where('account_id', '=', $account->id)
	        						->where('fb_campaign_id', '=', $fbAdSet->{AdSetFields::CAMPAIGN_GROUP_ID})
	                                ->first();

	        // if campaign not found, skip - NOT EXPECTED
	        if($campaign == null) continue;

	        // find the existing ad set in the database
	        $adSet = Adset::where('campaign_id', '=', $campaign->id)
	        					->where('fb_adset_id', '=', $fbAdSet->{AdSetFields::ID})
	        					->first();

	        // if not exists, create new one
	        if($adSet == null) {
	        	$adSet = new Adset;
	        	$adSet->campaign_id = $campaign->id;
	        	$adSet->name = $fbAdSet->{AdSetFields::NAME};
	        	$adSet->fb_adset_id = $fbAdSet->{AdSetFields::ID};
	        	$adSet->type = AdSet::TYPE_GET_VISITORS_ADS;
	        	// $adSet->created_at = $fbAdSet->{AdSetFields::CREATED_TIME};
	        }

	        // update the ad set status
	        $adSetStatus = $fbAdSet->{AdSetFields::CAMPAIGN_STATUS};
	        if($adSetStatus == 'CAMPAIGN_GROUP_ACTIVE') {
	        	$adSet->status = AdSet::STATUS_ACTIVE;
	        }
	        else {
	        	$adSet->status = AdSet::STATUS_PAUSED;	
	        }

			$adSet->budget_remaining = $fbAdSet->{AdSetFields::BUDGET_REMAINING};
			$adSet->lifetime_budget = $fbAdSet->{AdSetFields::LIFETIME_BUDGET};
			$adSet->daily_budget = $fbAdSet->{AdSetFields::DAILY_BUDGET};

            // save
            $adSet->save();

        }


        /*
         * Ads
         */

        $fbAds = AdsManager::getAds($fbAccount);

        foreach($fbAds as $fbAd) {

        	// find the existing adset - MUST EXIST
	        $adSet = Adset::where('campaign_id', '=', $campaign->id)
	        					->where('fb_adset_id', '=', $fbAd->{AdGroupFields::CAMPAIGN_ID})
	        					->first();

	        // if adset not found, skip - NOT EXPECTED
	        if($adSet == null) continue;

	        // find the existing ad in the database
	        $ad = Ad::where('ad_set_id', '=', $adSet->id)
	        					->where('fb_ad_id', '=', $fbAd->{AdGroupFields::ID})
	        					->first();

	        // if not exists, create new one
	        if($ad == null) {
	        	$ad = new Ad;
	        	$ad->ad_set_id = $adSet->id;
	        	$ad->name = $fbAd->{AdGroupFields::NAME};
	        	$ad->fb_ad_id = $fbAd->{AdGroupFields::ID};
	        	$ad->type = Ad::TYPE_NEWS_FEED_AD;
	        	// $ad->created_at = $fbAd->{AdGroupFields::CREATED_TIME};
	        }

	        // update the ad set status
	        $adStatus = $fbAd->{AdGroupFields::ADGROUP_STATUS};
	        if($adStatus == 'ACTIVE') {
	        	$ad->status = Ad::STATUS_ACTIVE;
	        }
	        else {
	        	$ad->status = Ad::STATUS_PAUSED;	
	        }

            // save
            $ad->save();
        }

	}

	// Get all campaigns 
	public static function getCampaigns($account)
    {
		$campaigns = $account->getAdCampaigns([
  			CampaignFields::NAME,
  			CampaignFields::STATUS
		]);

		return $campaigns;
	}

	// Get all adsets

	public static function getAdSets($account)
    {
		$adsets = $account->getAdSets([
			AdSetFields::CAMPAIGN_GROUP_ID,
			AdSetFields::NAME,
			AdSetFields::CAMPAIGN_STATUS,
			AdSetFields::PACING_TYPE,
			AdSetFields::ID,
			AdSetFields::CREATED_TIME,
			AdSetFields::DAILY_BUDGET,
			AdSetFields::LIFETIME_BUDGET,
			AdSetFields::BUDGET_REMAINING
		]);

		return $adsets;
	}

	// Get all ads

	public static function getAds($account)
    {
		$ads = $account->getAdGroups([
			AdGroupFields::CAMPAIGN_ID,
			AdGroupFields::NAME,
			AdGroupFields::ADGROUP_STATUS,
			AdGroupFields::ID,
			AdGroupFields::CREATED_TIME		
		]);

		return $ads;
	}
}