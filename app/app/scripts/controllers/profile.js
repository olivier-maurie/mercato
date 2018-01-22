'use strict';

/**
 * @ngdoc function
 * @name mercatoApp.controller:ProfileCtrl
 * @description
 * # ProfileCtrl
 * Controller of the mercatoApp
 */
angular.module('mercatoApp')
  .controller('ProfileCtrl', function ($scope, $location, userService) {
    userService.getUser()
      .then(function(response){ $scope.user = response[0]; });

    if($scope.password === $scope.password_valid){
      $scope.update = function(){
        userService.putUser($scope.pseudo, $scope.password)
          .then(function(response) {
            $location.path('/profile');
          })
          .catch(function(error) { console.error(error); });
      }
    }
  });
