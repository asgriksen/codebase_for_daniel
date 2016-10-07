'use strict';


angular.module('bungomedia.apis')
	.factory('User', ['$resource', function($resource) {
		return $resource('admin/users/:userId', {
			userId: '@id'
		}, {
			update: {
				method: 'PUT',
				url: 'admin/users'
			}
		});
	}]);