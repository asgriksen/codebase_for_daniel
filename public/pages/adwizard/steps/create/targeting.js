'use strict';

angular.module('bungomedia.controllers')
    .controller('adWizardCreateCampaignTargetingController', ['$rootScope', '$scope', '$q', 'Auth', 'Facebook', function ($rootScope, $scope, $q, Auth, Facebook) {
        $scope.onError = function (err) {
            $rootScope.onAPIError(err);
            $rootScope.hideWaiting();
        };

        $scope.includeLocation = null;
        $scope.excludeLocation = null;

        $scope.getLocation = function(q) {
            var deferred = $q.defer();
            Facebook.api(
                '/search', function (response) {
                    deferred.resolve(response.data);
                }, {access_token: $scope.account.fb_token, type: 'adgeolocation', location_types: ['country', 'region', 'city', 'zip'], q: q}
            );
            return deferred.promise;
        };

        $scope.addLocationClicked = function (include) {
            if (include == true) {
                $scope.targetingData.locations.includes.unshift(angular.copy($scope.includeLocation));
                $scope.includeLocation = null;
            } else {
                $scope.targetingData.locations.excludes.unshift(angular.copy($scope.excludeLocation));
                $scope.excludeLocation = null;
            }
        };

        $scope.refreshInterests = function(address) {
            var params = {address: address, sensor: false};
            return $http.get('http://maps.googleapis.com/maps/api/geocode/json', {params: params})
                .then(function(response) {
                    $scope.addresses = response.data.results
                });
        };

        $scope.interests = [];
        $scope.behaviors = [];
        $scope.connectionObjects = [];

        var init = function () {
            Facebook.api(
                '/search', function (response) {
                    $scope.interests = response.data;
                }, {access_token: $scope.account.fb_token, type: 'adTargetingCategory', class: 'interests'}
            );
            Facebook.api(
                '/search', function (response) {
                    $scope.behaviors = response.data;
                }, {access_token: $scope.account.fb_token, type: 'adTargetingCategory', class: 'behaviors'}
            );

            $scope.adAccountsPromise.then(function (adAccounts) {
                var adAccount = _.first(adAccounts);
                Facebook.api(
                    '/' + adAccount.id + '/connectionobjects', function (response) {
                        $scope.connectionObjects = response.data;
                    }, {access_token: $scope.account.fb_token}
                );
            });
        };

        init();
    }]);