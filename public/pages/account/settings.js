'use strict';

angular.module('bungomedia.controllers')
	.config(['$stateProvider', function($stateProvider) {
		$stateProvider
			.state('settings', {
				url: '/settings',
				templateUrl: '/pages/account/settings.html',
				controller: 'settingsController',
				resolve: {
					user: ['Auth', '$q', function(Auth, $q) {
						var deferred = $q.defer();

						Auth.me().$promise
							.then(function(user) {
								deferred.resolve(user);
							}, function(err) {
								deferred.resolve(null);
							});

						return deferred.promise;
					}]
				}
			});
	}])
	.controller('settingsController', ['$rootScope', '$scope', 'Auth', 'Utils', 'user', function($rootScope, $scope, Auth, Utils, user) {
		
		$rootScope.loadCurrentUser(true);

		$rootScope.clearMessage();

		var init = function() {
			$scope.user = user;
			$scope.user.password = '';
			$scope.password = '';
		};

		$scope.onSave = function() {
			$rootScope.clearMessage();

			if($scope.user.password != $scope.password) {
				return $rootScope.setError('Confirm password does not match.');
			}		

			Utils.showWaiting('Saving...');
			Auth.update({
				name: $scope.user.name,
				password: $scope.user.password
			}).$promise
				.then(function(user) {
					Utils.hideWaiting();
					$rootScope.loadCurrentUser(true);
					$rootScope.setMessage('User info has been saved.');
				}, function(err) {
					Utils.hideWaiting();
					$rootScope.onAPIError(err);
				});
		};

		init();
	}]);