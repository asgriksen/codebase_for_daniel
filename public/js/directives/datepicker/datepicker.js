'use strict';

angular.module('bungomedia.controllers')
  .directive('datepickerPopup', function() {
    return {
      restrict: 'A',
      link: function ($scope, $element, $attrs) {

        var init = function() {

          $scope.today();

          $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1,
            'class': 'datepicker'
          };

          $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
          $scope.format = $scope.formats[0];

        };
        $scope.today = function() {
          $scope.dt = new Date();
        };

        $scope.clear = function () {
          $scope.dt = null;
        };

        // Disable weekend selection
        $scope.disabled = function(date, mode) {
          return false;
        };

        $scope.open = function($event) {
          $event.preventDefault();
          $event.stopPropagation();

          $scope.opened = true;
        };

        init();
      }
    }
  });

    