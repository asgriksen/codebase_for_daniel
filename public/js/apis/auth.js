'use strict';


angular.module('bungomedia.apis')
	.factory('Auth', ['$resource', function($resource) {
		return $resource('api/users', {

		}, {
			login: {
				method: 'POST',
				url: 'api/users/auth'
			},
			logout: {
				method: 'GET',
				url: 'api/users/logout'
			},
			online: {
				method: 'POST',
				url: 'api/users/online'
			},
			me: {
				method: 'GET',
				url: 'api/users/me'
			},
			update: {
				method: 'POST',
				url: 'api/users/me'
			}
		});
	}]);