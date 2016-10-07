'use strict';

angular.module('bungomedia.controllers')
	.config(['$stateProvider', function($stateProvider) {
		$stateProvider
			.state('statistics', {
				url: '/statistics/:subpage',
				templateUrl: '/pages/statistics/statistics.html',
				controller: 'statisticsController',
				resolve: {
					subpage: ['$stateParams', function($stateParams) {
						return (typeof $stateParams.subpage === 'undefined') || $stateParams.subpage == '' ? 'campaigns' : $stateParams.subpage;
					}]
				}
			});
	}])
	.controller('statisticsController', ['$rootScope', '$scope', '$q', 'Auth', 'Utils', 'subpage', 'METRICS_FIELDS', 'COMPARISON_OPERATORS', 'STATISTICS_TABLE_COLUMNS', 'ADS_STATUS', 'GraphDataService', 'MetricsDataService', 'DialogService', function($rootScope, $scope, $q, Auth, Utils, subpage, METRICS_FIELDS, COMPARISON_OPERATORS, STATISTICS_TABLE_COLUMNS, ADS_STATUS, GraphDataService, MetricsDataService, DialogService) {
		
		$rootScope.loadCurrentUser(true);

		var init = function() {
			$scope.subpage = subpage;
			$scope.METRICS_FIELDS = METRICS_FIELDS;
			$scope.COMPARISON_OPERATORS = COMPARISON_OPERATORS;
			$scope.ADS_STATUS = ADS_STATUS;
		};

		$scope.switchTab = function(page) {
			$scope.subpage = page;
		};

		$scope.filterData = function(data, filters) {
			return data;
		};
		$scope.renderGraph = function(data, filters) {
			var graphdata;
			var aggregatedData = MetricsDataService.aggregateDataByDate(
				_.pluck(data, 'metrics')
			);
			
			graphdata = GraphDataService.getMetricsGraphData(aggregatedData, filters);

			return graphdata;
		};
		$scope.renderTable = function(data, filters, type) {
			var tabledata = {

			};
			var fields = $rootScope.UserPreferences.get(type + '_TABLE_FIELDS', true);
			var fields_meta = MetricsDataService.getFieldsMeta(type);

			// $scope.$apply(function() {
				tabledata.fields = _.map(fields, function(field) {
					return {
						field: field,
						label: fields_meta[field],
						type: MetricsDataService.getFieldType(field),
						renderer: MetricsDataService.getMetricFieldRenderer(field)
					};
				});
				tabledata.dataset = _.each($scope.filterData(data, filters.table), function(item) {
					var metric = MetricsDataService.aggregateData(item.metrics);
					return _.extend(item, metric);
				});
			// });
			return tabledata;
		};

		$scope.showTableSettings = function(type) {
			var deferred = $q.defer();
			var fields = $rootScope.UserPreferences.get(type + '_TABLE_FIELDS', true);
			DialogService.openTableColumnPreferenceDialog(STATISTICS_TABLE_COLUMNS[type], fields)
				.then(function(selectedColumns) {
					$rootScope.showWaiting();
					$rootScope.UserPreferences.set(type + '_TABLE_FIELDS', selectedColumns)
						.then(function() {
							$rootScope.loadCurrentUser()
								.then(function() {
									$rootScope.hideWaiting();
									deferred.resolve(selectedColumns);		
								}, function(err) {
									$rootScope.hideWaiting();
									deferred.resolve(selectedColumns);
								});							
						}, function(err) {
							$rootScope.hideWaiting();
							$rootScope.onAPIError(err);
							deferred.reject();
						});
				}, function() {
					deferred.reject();
				});
			return deferred.promise;
		};

		init();
	}]);