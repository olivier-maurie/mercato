'use strict';

angular.module('mercatoApp')
  .filter('num', function () {
    return function(input) {
      return parseInt(input, 10);
    };
  });
