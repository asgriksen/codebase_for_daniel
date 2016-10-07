'use strict';


angular.module('bungomedia.controllers')
	.factory('MetricsDataService', ['$filter', 'ADS_STATUS', 'METRICS_FIELDS', function($filter, ADS_STATUS, METRICS_FIELDS) {
		var MetricsDataService = {
			aggregateDataByDate: function(data) {
				return _
					.chain(data)
					.reduceRight(function(a, b) { return a.concat(b); }, [])
					.groupBy(function(item) { return item.date_start/* + '~' + item.date_stop*/; })
					.map(function(items, date) {
						var aggregatedRow = MetricsDataService.aggregateData(items);
						aggregatedRow.date = date;
						return aggregatedRow;
					})
					.value();
			},
			aggregateData: function(data) {
				return _.reduceRight(data, function(a, b) {
					return _.mapValues(a, function(val, field) {
						if(typeof b[field] == 'undefined') return val;

						var aggregationType = MetricsDataService.getAggregationType(field);
						var fnAggregate = function(oldval, newval, type, length) {
							switch(true) {
								case (type == 'SUM'): return oldval + newval;
								case (type == 'AVG'): return oldval + (newval/length);
								case (type == 'MIN'): return oldval > newval ? newval : oldval;		// need to be fixed; if b is the first then choose b
								case (type == 'MAX'): return oldval < newval ? newval : oldval;
							}
						};

						if(_.isArray(val)) {
							return _.mapValues(val, function(item) {
								var newval = _.findWhere(b[field], {action_type: item.action_type});
								if(typeof newval == 'undefined') return item.value;
								return fnAggregate(item.value, newval.value, aggregationType, data.length);
							});
						}
						else if(_.isNumber(val)) {
							return fnAggregate(val, b[field], aggregationType, data.length);
						}
						else {
							return val;
						}
					});
				}, MetricsDataService.empty());
			},
			filter: function(data, filters) {
				return _
					.chain(data)
					.filter(function(row) {
						for (var field in filters) {
							var filter = filters[field];
							var value = row[field];
							if( (typeof filter.value == 'undefined') || filter.value === '' || filter.value === null ) { continue; }
							if( typeof value === 'undefined' ) return false;

							var fieldtype = MetricsDataService.getFieldType(field);
							if(fieldtype == 'STRING') {							
								var a = filter.value.toString().toLowerCase();
								var b = value.toString().toLowerCase();
								if(b.indexOf(a) === -1) return false;
							}
							else if(fieldtype == 'DATE') {
								var a = moment(filter.value);
								var b = moment(value);
								if(a.year() != b.year() || a.month() != b.month() || a.date() != b.date()) return false;
							}
							else if(fieldtype == 'STATUS') {
								if(value != filter.value) return false;
							}
							else if(fieldtype == 'METRIC') {
								if( typeof filter.operator == 'undefined' ) continue;
								if(filter.operator == 'GTE' && !(value >= filter.value)) return false;
								if(filter.operator == 'GT' && !(value > filter.value)) return false;
								if(filter.operator == 'LTE' && !(value <= filter.value)) return false;
								if(filter.operator == 'LT' && !(value < filter.value)) return false;
								if(filter.operator == 'EQ' && !(value == filter.value)) return false;
								if(filter.operator == 'NEQ' && !(value != filter.value)) return false;
							}
						}
						
						return true;
					})
					.value();
			},
			getAggregationType: function(metric) {
				switch(true) {
					case metric == 'ctr':
					case metric == 'roi':
						return 'AVG';
				}
				return 'SUM';
				// AVG
				// MIN
				// MAX
			},
			getFieldType: function(field) {
				switch(true) {
					case (field == 'name'): return 'STRING';
					case (field == 'fb_created_at'): return 'DATE';
					case (field == 'status'): return 'STATUS';
				}
				return 'METRIC';
			},
			getMetricFieldRenderer: function(field) {
				var currencyRenderer = function(fraction) {
					return function(val) {
						return $filter('currency')(val, '$', fraction);
					};
				};
				var numberRenderer = function(fraction) {
					return function(val) {
						return $filter('number')(val, fraction);
					};
				};
				var percentageRenderer = function(fraction) {
					return function(val) {
						return $filter('number')(val, fraction) + '%';
					};
				};
				var dateRenderer = function(format) {
					return function(val) {
						return $filter('date')(val, format);
					};
				};
				var enumRenderer = function(enums) {
					return function(val) {
						return enums[val];
					};
				};
				var generalRenderer = function(val) {
					return val;
				};

				var type = MetricsDataService.getFieldType(field);

				if(type == 'STRING') return generalRenderer;
				else if(type == 'DATE') return dateRenderer('MMMM d, yyyy');
				else if(type == 'STATUS') return enumRenderer(ADS_STATUS);
				else if(type == 'METRIC') {
					switch(true) {
						case (field == 'bidding'):
						case (field == 'cpc'):
						case (field == 'cpm'):
							return currencyRenderer(4);
						case (field == 'revenue'):
							return currencyRenderer(2);
						case (field == 'clicks'):
						case (field == 'inline_link_clicks'):
						case (field == 'call_to_action_clicks'):
							return numberRenderer(0);
						case (field == 'ctr'):
						case (field == 'roi'):
							return percentageRenderer(4);
					}
				}
				return generalRenderer;
			},
			getFieldsMeta: function(type) {
				var fields = _.clone(METRICS_FIELDS);
				return _.extend(fields, {
					name: 'Name',
					fb_created_at: 'Created at',
					status: 'Status',
					bidding: 'Bid'
				});
			},
			empty: function() {
				return {
					bidding: 0,
					actions: [{
							action_type: "comment",
							value: 0
						},{
							action_type: "link_click",
							value: 0
						},{
							action_type: "post",
							value: 0
						},{
							action_type: "post_like",
							value: 0
						},{
							action_type: "page_engagement",
							value: 0
						},{
							action_type: "post_engagement",
							value: 0
						}
					],
					app_store_clicks: 0,
					clicks: 0,
					call_to_action_clicks: 0,
					inline_link_clicks: 0,
					cost_per_action_type: [{
							action_type: "comment",
							value: 0
						},{
							action_type: "link_click",
							value: 0
						},{
							action_type: "post",
							value: 0
						},{
							action_type: "post_like",
							value: 0
						},{
							action_type: "page_engagement",
							value: 0
						},{
							action_type: "post_engagement",
							value: 0
						}
					],
					cost_per_total_action: 0,
					cost_per_unique_click: 0,
					cpc: 0,
					cpm: 0,
					cpp: 0,
					ctr: 0,
					deeplink_clicks: 0,
					frequency: 0,
					impressions: 0,
					reach: 0,
					roi: 0,
					social_clicks: 0,
					social_impressions: 0,
					social_reach: 0,
					spend: 0,
					total_action_value: 0,
					total_actions: 0,
					total_unique_actions: 0,
					unique_actions: [{
							action_type: "comment",
							value: 0
						},{
							action_type: "link_click",
							value: 0
						},{
							action_type: "post",
							value: 0
						},{
							action_type: "post_like",
							value: 0
						},{
							action_type: "page_engagement",
							value: 0
						},{
							action_type: "post_engagement",
							value: 0
						}
					],
					unique_clicks: 0,
					unique_ctr: 0,
					unique_impressions: 0,
					unique_social_clicks: 0,
					unique_social_impressions: 0,
					website_clicks: 0,
					website_ctr: [{
							action_type: "comment",
							value: 0
						},{
							action_type: "link_click",
							value: 0
						},{
							action_type: "post",
							value: 0
						},{
							action_type: "post_like",
							value: 0
						},{
							action_type: "page_engagement",
							value: 0
						},{
							action_type: "post_engagement",
							value: 0
						}
					]
				}
			}
		};

		return MetricsDataService;
	}]);