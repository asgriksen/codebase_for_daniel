'use strict';

angular.module('bungomedia.controllers')
	.config(['$stateProvider', function($stateProvider) {
		$stateProvider
			.state('rules', {
				url: '/rules',
				templateUrl: '/pages/rules/list.html',
				controller: 'automaticRulesController',
				resolve: {
				}
			});
	}])
	.controller('automaticRulesController', ['$rootScope', '$scope', '$q', 'Auth', 'Utils', 'Rule', function($rootScope, $scope, $q, Auth, Utils, Rule) {
		
		$rootScope.loadCurrentUser(true);
		
		var init = function() {
			$scope.loadData();
		};

		$scope.loadData = function() {
			$rootScope.showWaiting();
			Rule.query()
				.$promise
				.then(function(rules) {
					$rootScope.hideWaiting();
					$scope.rules = rules;
					console.log(rules);
				}, function(err) {
					$rootScope.hideWaiting();
					$rootScope.onAPIError(err);
					$scope.rules = [];
				});
		};

		init();
	}]);