'use strict';


angular.module('bungomedia.apis')
    .factory('Category', ['$resource', function($resource) {
        return $resource('api/cart-categories/:id', {
            id: '@id'
        }, {

        });
    }]);