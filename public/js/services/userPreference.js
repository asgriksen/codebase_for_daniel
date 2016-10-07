'use strict';


angular.module('bungomedia.controllers')
	.factory('UserPreferenceService', [ '$filter', function($filter) {
		var UserPreferenceService = {
			getDefaultPreference: function(key) {
				switch(true) {
					case (key == 'CAMPAIGN_TABLE_FIELDS'): {
						return [
							'name',
							'fb_created_at',
							'status',
							'bidding',
							'inline_link_clicks',
							'ctr',
							'cpc',
							'cpm',
							'revenue',
							'roi'
						];
						break;
					}
					case (key == 'ADSET_TABLE_FIELDS'): {
						return [
							'name',
							'fb_created_at',
							'status',
							'bidding',
							'inline_link_clicks',
							'ctr',
							'cpc',
							'cpm',
							'revenue',
							'roi'
						];
						break;
					}
					case (key == 'AD_TABLE_FIELDS'): {
						return [
							'name',
							'fb_created_at',
							'status',
							'bidding',
							'inline_link_clicks',
							'ctr',
							'cpc',
							'cpm',
							'revenue',
							'roi'
						];
						break;
					}
				}
				return null;
			}
		};

		return UserPreferenceService;
	}]);