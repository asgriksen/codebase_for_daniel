'use strict';


angular.module('bungomedia.apis')
	.factory('AdWidzard', ['$resource', function($resource) {
		return $resource('api/adwizards/:id', {
			id: '@id'
		}, {
			update: {
				method: 'PUT',
				url: 'api/adwizards/:id'
			}
		});
	}]);