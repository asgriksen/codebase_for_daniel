'use strict';

angular.module('bungomedia.controllers')
	.directive('metricsDataTable', ['METRICS_FIELDS', 'COMPARISON_OPERATORS', 'ADS_STATUS', 'MetricsDataService', function(METRICS_FIELDS, COMPARISON_OPERATORS, ADS_STATUS, MetricsDataService) {
		return {
			restrict: 'E',
			templateUrl: 'js/directives/metrics-data-table/metrics-data-table.html',
			scope: {
				dataset: '=ngDataset',
				checkedRows: '=ngCheckedRows',
				expandable: '@ngExpandable'
			},
			link: function ($scope, $element, $attrs) {
				var init = function() {
					$scope.METRICS_FIELDS = METRICS_FIELDS;
					$scope.ADS_STATUS= ADS_STATUS;
					$scope.filter = {};
					$scope.isCheckedAll = false;
				};

				var render = function() {
					// calculate total
					$scope.data = MetricsDataService.filter($scope.dataset.dataset, $scope.filter);
					$scope.total = MetricsDataService.aggregateData($scope.data);
				};

				$scope.onCheckRow = function() {
					$scope.isCheckedAll = _.difference($scope.data, $scope.checkedRows).length == 0;
				};
				$scope.onCheckAllClick = function(checked) {
					
					// $scope.checkedRows = [];
					while($scope.checkedRows.length > 0) $scope.checkedRows.pop();
					if($scope.isCheckedAll) {
						_.each($scope.data, function(row) {
							$scope.checkedRows.push(row);
						});
					}
				};

				$scope.$watch('dataset', render, true);
				$scope.$watch('filter', render, true);

				init();
			}
		}
	}])
	.directive('metricsSelect', function() {
		return {
      restrict: 'E',
      template: '<select class="form-control" ng-options="key as label for (key, label) in options" ng-model="model"><option value="">--Select--</option></select>',
      scope: {
      	model: '=model',
      	options: '=options'
      },
      link: function ($scope, $element, $attrs) {
      }
    };
	})
	.directive('metricComparisonSelect', function() {
		return {
      restrict: 'E',
      template: '<select class="form-control" ng-model="model"><option value="GTE">&#8805;</option><option value="GT">&#62;</option><option value="LTE">&#8804;</option><option value="LT">&#60;</option><option value="EQ">&#61;</option><option value="NEQ">&#8800;</option><option value="">Range</option></select>',
      scope: {
      	model: '=model'
      },
      link: function ($scope, $element, $attrs) {
      }
    };
	});