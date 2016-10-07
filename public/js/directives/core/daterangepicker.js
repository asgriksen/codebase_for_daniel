'use strict';

/**
 * @ngdoc directive
 * @name minovateApp.directive:daterangepicker
 * @description
 * # daterangepicker
 */
angular.module('minovateApp')
  .directive('daterangepicker', function() {
    return {
      restrict: 'A',
      scope: {
        options: '=daterangepicker',
        start: '=dateBegin',
        end: '=dateEnd'
      },
      link: function(scope, element) {
        if(scope.options == null || (typeof scope.options === 'undefined') || scope.options == '') {
          scope.options = {};
        }
        scope.options.startDate = scope.start;
        scope.options.endDate = scope.end;
        scope.options = _.extend({
          format: 'YYYY-MM-DD'
        }, scope.options);
        element.daterangepicker(scope.options, function(start, end) {
          scope.start = start.format(this.format);
          scope.end = end.format(this.format);
          scope.$apply();
        });
      }
    };
  });

