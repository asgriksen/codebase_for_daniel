'use strict';

angular.module('bungomedia.controllers')
    .controller('adWizardCreateCampaignSettingsController', ['$rootScope', '$scope', '$q', '$modal', 'Auth', 'Utils', 'Cart', 'Category', 'DialogService', 'Account', 'Facebook', 'CAMPAIGN_TYPES', 'CAMPAIGN_OPTIMIZE_FORS', 'CAMPAIGN_ENDS', function ($rootScope, $scope, $q, $modal, Auth, Utils, Cart, Category, DialogService, Account, Facebook, CAMPAIGN_TYPES, CAMPAIGN_OPTIMIZE_FORS, CAMPAIGN_ENDS) {
        $scope.CAMPAIGN_TYPES = CAMPAIGN_TYPES;
        $scope.CAMPAIGN_OPTIMIZE_FORS = CAMPAIGN_OPTIMIZE_FORS;
        $scope.CAMPAIGN_ENDS = CAMPAIGN_ENDS;

        $scope.onError = function (err) {
            $rootScope.onAPIError(err);
            $rootScope.hideWaiting();
        };

        $scope.removeDayOfWeekScheduleClicked = function (day_of_week) {
            $scope.campaignData.schedule.days_of_week = _.without($scope.campaignData.schedule.days_of_week, _.findWhere($scope.campaignData.schedule.days_of_week, day_of_week));
        };

        $scope.newDayOfWeekScheduleClicked = function () {
            $scope.campaignData.schedule.days_of_week.push(angular.copy($scope.defaultData.schedule.day_of_week));
        };

        var init = function () {
        };

        init();
    }]);