'use strict';

angular.module('bungomedia.controllers')
    .controller('adWizardGoalController', ['$rootScope', '$scope', 'Auth', 'Utils', function($rootScope, $scope, Auth, Utils) {
        $scope.onNextStep = function(type) {
            $scope.campaignData.type = type;
            $scope.proceedNextStep();
        };
    }]);