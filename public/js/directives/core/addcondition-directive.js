
'use strict';

/**
 * @ngdoc directive
 * @name minovateApp.directive:activateButton
 * @description
 * # activateButton
 */
angular.module('minovateApp')
  .directive("addcondition", function($compile){
  return function(scope, element, attrs){
    element.bind("click", function(){
      angular.element(document.getElementById('facebook-stats-wrapper')).append($compile("<div class='form-group clearfix'><div class='col-sm-5'><select class='form-control'><option>Facebook Statistics</option><option>Facebook Statistics</option><option>Facebook Statistics</option><option>Facebook Statistics</option></select></div><div class='col-sm-2'><select class='form-control'><option>10</option><option>12</option><option>123</option><option>12</option></select></div><div class='col-sm-4'><input type='text' class='form-control'></div><div class='col-sm-1'><a href='#' class='btn btn-default glyphicon glyphicon-remove' style='vertical-align: bottom;'></a></div></div>")(scope));
    });
  };
})  
  .directive("newaction", function($compile){
  return function(scope, element, attrs){
    element.bind("click", function(){
      angular.element(document.getElementById('run-action-wrapper')).append($compile("<div class='form-group clearfix'><div class='col-sm-3'><select class='form-control'><option>Run</option><option>Run</option><option>Run</option><option>Run</option></select></div><div class='col-sm-4'><select class='form-control'><option>Absolute Value</option><option>Absolute Value</option><option>Absolute Value</option><option>Absolute Value</option></select></div><div class='col-sm-4'><input type='text' class='form-control'></div><div class='col-sm-1'><a href='#' class='btn btn-default glyphicon glyphicon-remove' style='vertical-align: bottom;'></a></div></div>")(scope));
    });
  };
});
/*    .directive("removecondition", function($compile){
  return function(scope, element, attrs){
    element.bind("click", function(){
        console.log(element);
      angular.element(document.getElementById('facebook-stats-wrapper')).append($compile("hi")(scope));
    });
  };
});*/

