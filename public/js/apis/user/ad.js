'use strict';


angular.module('bungomedia.apis')
	.factory('Ad', ['$resource', function($resource) {
		return $resource('api/ads/:id', {
			id: '@id'
		}, {
			update: {
				method: 'PUT',
				url: 'api/ads/:id'
			}
		});
	}]);