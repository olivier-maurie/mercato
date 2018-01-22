'use strict';

/**
 * @ngdoc filter
 * @name mercatoApp.filter:unique
 * @function
 * @description
 * # unique
 * Filter in the mercatoApp.
 */
angular.module('mercatoApp')
  .filter('unique', function () {
    return function (input) {
      return 'unique filter: ' + input;
    };
  });
