'use strict';

angular.module('bungomedia.controllers')
	.controller('AppController', ['$rootScope', '$state', 'Auth', 'UserPreference', 'UserPreferenceService', '$q', '$cookies', function ($rootScope, $state, Auth, UserPreference, UserPreferenceService, $q, $cookies) {

	var TRACKING_INTERVAL = 30000;
	// Check empty
	$rootScope.isEmpty = function(val) {
		return val === '' || typeof val === "undefined" || typeof val === null;
	};

	// Show error
	$rootScope.setError = function(error) {
		$rootScope.error = error;
	};
	// Show message
	$rootScope.setMessage = function(msg) {
		$rootScope.message = msg;
	};

	// Clear all messages
	$rootScope.clearMessage = function() {
		$rootScope.setMessage('');
		$rootScope.setError('');
	};

	// load current user and preferences
	$rootScope.loadCurrentUser = function(redirect) {
		var deferred = $q.defer();

		Auth.me().$promise
			.then(function(user) {
				$rootScope.currentUser = user;
				UserPreference.query().$promise
					.then(function(preferences) {
						$rootScope.currentUser.preferences = preferences;
						deferred.resolve($rootScope.currentUser);
					}, function(err) {
						$rootScope.currentUser.preferences = [];
						deferred.resolve($rootScope.currentUser);
					});
			}, function(err) {
				if(redirect) {
					window.location = '/login';
				}
			});

		return deferred.promise;
	};

	$rootScope.onAPIError = function(err) {
		if(err.status == 500) {
			$rootScope.setError(err.statusText);
		}
		else {
			$rootScope.setError('Unexpected error occured. Please contact to system administrator.');
		}

		console.log(err);
	};

	// Check permission and redirect
	$rootScope.checkPermission = function(type) {
		if($rootScope.currentUser.type !== type) {
			$state.go('dashboard');
		}
	};

	// Cookie management
	$rootScope.cookies = {
		get: function(key, defaultVal) {
			var val = $cookies[key];
			if(val == null || (typeof val === 'undefined')) return defaultVal;
			return val;
		},
		put: function(key, val) {
			$cookies[key] = val;
		}
	};

	// User preference functions
	$rootScope.UserPreferences = {
		// get preference - return default preference if not exists
		get: function (key, expectJSON) {
			var val = _.findWhere($rootScope.currentUser.preferences, {key: key});

			if(typeof val === 'undefined') val = UserPreferenceService.getDefaultPreference(key);
			else val = val.value;

			if(expectJSON === true) {
				if(!_.isObject(val) && !_.isArray(val)) {
					val = JSON.parse(val);
				}
			}
			return val;
		},
		// set preference
		set: function(key, value) {
			if(_.isObject(value) || _.isArray(value)) value = JSON.stringify(value);
			var deferred = $q.defer();
			var val = new UserPreference({
				key: key,
				value: value
			});
			val.$update()
				.then(function(val) {
					deferred.resolve(val);
				}, function(err) {
					deferred.reject(err);
				});

			return deferred.promise;
		}
	};

	// start tracking online status every 30 seconds
	var startTrackingOnline = function() {
		if($rootScope.currentUser) {
			Auth.online().$promise
				.then(function() {
					setTimeout(startTrackingOnline, TRACKING_INTERVAL);
				}, function(err) {
					setTimeout(startTrackingOnline, TRACKING_INTERVAL);
				});
		}
	};

	// show/hide loading icon
	$rootScope.showWaiting = function() {
		angular.element('#pageloader').toggleClass('hide animate');
	};
	$rootScope.hideWaiting = function() {
		angular.element('#pageloader').toggleClass('hide animate');
	};

	$rootScope.$on('$stateChangSuccess', function(event, toState, toParams, fromState, fromParams) {
		$rootScope.clearMessage();
	});

	// init
	var init = function() {
		$rootScope.clearMessage();

		$rootScope.loadCurrentUser(false);

		$rootScope.main = {
			title: 'Minovate',
			settings: {
				navbarHeaderColor: 'scheme-default',
				sidebarColor: 'scheme-default',
				brandingColor: 'scheme-default',
				activeColor: 'default-scheme-color',
				headerFixed: true,
				asideFixed: true,
				rightbarShow: false
			}
		};

		setTimeout(startTrackingOnline, TRACKING_INTERVAL);
	}


	init();

}]);