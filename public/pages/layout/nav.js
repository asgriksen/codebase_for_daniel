'use strict';

angular.module('bungomedia.controllers')
	.controller('layoutNavController', function ($scope) {
		$scope.oneAtATime = false;

		$scope.status = {
			isFirstOpen: true,
			isSecondOpen: true,
			isThirdOpen: true
		};
	});