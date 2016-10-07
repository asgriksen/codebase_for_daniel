'use strict';

angular.module('bungomedia.controllers')
	.constant('METRICS_FIELDS', {
		inline_link_clicks: 'Clicks - Inline',
		call_to_action_clicks: 'Clicks - CTA',
		// Conversion Rate
		cpc: 'CPC',
		cpm: 'CPT',
		ctr: 'CTR',
		frequency: 'Frequency',
		impressions: 'Impressions',
		// Per Click Revenue
		revenue: 'Revenues',
		roi: 'ROI',
		spend: 'Spend'
		// 'Transactions',
	})
	.constant('COMPARISON_OPERATORS', {
		GTE: '&#8805;',
		GT: '&#62;',
		LTE: '&#8804;',
		LT: '&#60;',
		EQ: '&#61;',
		NEQ: '&#8800;'
	})
	.constant('ADS_STATUS', {
		PAUSED: 'Paused',
		ACTIVE: 'Active',
		ARCHIVED: 'Archived',
		DELETED: 'Deleted'
	})
    .constant('CAMPAIGN_TYPES', {
        CONTINUE: 'CONTINUE',
        START_END: 'START_END',
        DAYS_OF_WEEK: 'DAYS_OF_WEEK'
    })
    .constant('CAMPAIGN_OPTIMIZE_FORS', {
        NONE: 'None',
        APP_INSTALLS: 'App Installs',
        CLICKS: 'Clicks',
        ENGAGED_USERS: 'Engaged Users',
        EXTERNAL: 'External',
        EVENT_RESPONSES: 'Event Responses',
        IMPRESSIONS: 'Impressions',
        LINK_CLICKS: 'Link Clicks',
        OFFER_CLAIMS: 'Offer Claims',
        OFFSITE_CONVERSIONS: 'Offsite Conversions',
        PAGE_ENGAGEMENT: 'Page Engagement',
        PAGE_LIKES: 'Page Likes',
        POST_ENGAGEMENT: 'Post Engagement',
        REACH: 'Reach',
        SOCIAL_IMPRESSIONS: 'Social Impressions',
        VIDEO_VIEWS: 'Video Views'
    })
    .constant('CAMPAIGN_ENDS', {
        PAUSE: 'Pause',
        DELETE: 'Delete',
        NOTHING: 'Nothing'
    })
	.constant('STATISTICS_TABLE_COLUMNS', {
		CAMPAIGN: [{
			group: 'Campaign',
			columns: [
				{ name: 'Name', column: 'name' },
				{ name: 'Created', column: 'fb_created_at' },
				{ name: 'Status', column: 'status' }
			]
		},{
			group: 'Facebook Statistics',
			columns: [
				{ name: 'Bid', column: 'bidding' },
				{ name: 'Clicks - Inline', column: 'inline_link_clicks' },
				{ name: 'Clicks - CTA', column: 'call_to_action_clicks' },
				{ name: 'CTR', column: 'ctr' },
				{ name: 'CPC', column: 'cpc' },
				{ name: 'CPT', column: 'cpm' },
				{ name: 'Impressions', column: 'impressions' },
				{ name: 'Frequency', column: 'frequency' },
				{ name: 'Spend', column: 'spend' },
				{ name: 'Reach', column: 'reach' }
			]
		},{
			group: 'Conversions',
			columns: [
				{ name: 'Revenue', column: 'revenue' },
				{ name: 'ROI', column: 'roi' },
				{ name: 'Transactions', column: 'transactions' },
				{ name: 'COS', column: 'cos' },
				{ name: 'CPT', column: 'cpt' },
				{ name: 'Per click value', column: 'per_click_value' },
				{ name: 'Conversion rate', column: 'conversion_rate' }
			]
		}],
		ADSET: [{
			group: 'Ad Set',
			columns: [
				{ name: 'Name', column: 'name' },
				{ name: 'Created', column: 'fb_created_at' },
				{ name: 'Status', column: 'status' }
			]
		},{
			group: 'Facebook Statistics',
			columns: [
				{ name: 'Bid', column: 'bidding' },
				{ name: 'Clicks - Inline', column: 'inline_link_clicks' },
				{ name: 'Clicks - CTA', column: 'call_to_action_clicks' },
				{ name: 'CTR', column: 'ctr' },
				{ name: 'CPC', column: 'cpc' },
				{ name: 'CPT', column: 'cpm' },
				{ name: 'Impressions', column: 'impressions' },
				{ name: 'Frequency', column: 'frequency' },
				{ name: 'Spend', column: 'spend' },
				{ name: 'Reach', column: 'reach' }
			]
		},{
			group: 'Conversions',
			columns: [
				{ name: 'Revenue', column: 'revenue' },
				{ name: 'ROI', column: 'roi' },
				{ name: 'Transactions', column: 'transactions' },
				{ name: 'COS', column: 'cos' },
				{ name: 'CPT', column: 'cpt' },
				{ name: 'Per click value', column: 'per_click_value' },
				{ name: 'Conversion rate', column: 'conversion_rate' }
			]
		}],
		AD: [{
			group: 'Ad',
			columns: [
				{ name: 'Name', column: 'name' },
				{ name: 'Created', column: 'fb_created_at' },
				{ name: 'Status', column: 'status' }
			]
		},{
			group: 'Facebook Statistics',
			columns: [
				{ name: 'Bid', column: 'bidding' },
				{ name: 'Clicks - Inline', column: 'inline_link_clicks' },
				{ name: 'Clicks - CTA', column: 'call_to_action_clicks' },
				{ name: 'CTR', column: 'ctr' },
				{ name: 'CPC', column: 'cpc' },
				{ name: 'CPT', column: 'cpm' },
				{ name: 'Impressions', column: 'impressions' },
				{ name: 'Frequency', column: 'frequency' },
				{ name: 'Spend', column: 'spend' },
				{ name: 'Reach', column: 'reach' }
			]
		},{
			group: 'Conversions',
			columns: [
				{ name: 'Revenue', column: 'revenue' },
				{ name: 'ROI', column: 'roi' },
				{ name: 'Transactions', column: 'transactions' },
				{ name: 'COS', column: 'cos' },
				{ name: 'CPT', column: 'cpt' },
				{ name: 'Per click value', column: 'per_click_value' },
				{ name: 'Conversion rate', column: 'conversion_rate' }
			]
		}]
	})
    .constant('REVENUE_FILTER', [
        {value: 'day', name: 'Daily'},
        {value: 'week', name: 'Weekly'},
        {value: 'month', name: 'Monthly'}
    ]);