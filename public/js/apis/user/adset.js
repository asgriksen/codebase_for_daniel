'use strict';


angular.module('bungomedia.apis')
	.factory('AdSet', ['$resource', function($resource) {
		return $resource('api/ad-sets/:id', {
			id: '@id'
		}, {
			update: {
				method: 'PUT',
				url: 'api/ad-sets/:id'
			}
		});
	}]);