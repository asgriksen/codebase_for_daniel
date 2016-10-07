<?php

namespace App\Support\Facebook;

use Facebook\Facebook;
use Facebook\Authentication\AccessToken;

class TokenManager {


	public static $sharedInstance = null;

	protected $_fb;

	protected $_fbApp;

	protected $_fbOAuth2Client;

	public static function getSharedInstance() {
		if(TokenManager::$sharedInstance == null) {
			TokenManager::$sharedInstance = new TokenManager();
		}

		return TokenManager::$sharedInstance;
	}

	public function __construct() {
		$this->_fb = new Facebook([
                'app_id' => config("facebook.app_id"),
                'app_secret' => config('facebook.secret'),
                'default_graph_version' => 'v2.3'
            ]);

        $this->_fbApp = $this->_fb->getApp();

        $this->_fbOAuth2Client = $this->_fb->getOAuth2Client();
	}

	public function extendAccessToken($account) {
        $accessToken = new AccessToken($account->fb_token);

        try {
            $newAccessToken = $this->_fbOAuth2Client->getLongLivedAccessToken($accessToken);
            
            $account->fb_token = $newAccessToken->getValue();
            $account->fb_token_expiry = $newAccessToken->getExpiresAt()->format(DB_DATE_FORMAT);
            $account->save();

            return true;
        } catch(FacebookSDKException $e) {
            return false;
        }
    }

    public function isValidAccessToken($account) {
        $accessToken = new AccessToken($account->fb_token);

        $accessTokenInfo = $this->_fbOAuth2Client->debugToken($accessToken);
        // print_r($accessTokenInfo);
        if(!$accessTokenInfo->getIsValid()) return false;

        try {
            $accessTokenInfo->validateExpiration();
            return true;
        } catch(FacebookSDKException $e) {
            return false;
        }
    }
}