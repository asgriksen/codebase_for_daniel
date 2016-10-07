'use strict';

angular.module('bungomedia.controllers')
	.controller('loginController', ['$rootScope', '$scope', 'Auth', 'Utils', function($rootScope, $scope, Auth, Utils) {
		
		var init = function() {
			$scope.user = {
				email: $rootScope.cookies.get('email'),
				password: '',
				remember: false
			};

			$scope.error = '';
		}

		$scope.login = function() {
			$scope.error = '';
			Utils.showWaiting('Logging in...');
			Auth.login($scope.user).$promise
				.then(function(user) {
					$rootScope.cookies.put('email', $scope.user.email);
					Utils.hideWaiting();
					window.location = '/';
				}, function(err) {
					Utils.hideWaiting();
					console.log(err);
					$scope.error = 'Invalid email/password.';
				});
		};

		init();
	}]);