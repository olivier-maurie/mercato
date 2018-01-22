'use strict';

angular.module('mercatoApp')
  .controller('TeamCtrl', function ($scope, $location, userService) {

    userService.getPlayerByUser()
      .then(function(response){
        $scope.playersTab = response;
        return $scope.playersTab;
      })
      .catch(function(error) { console.log(error); });
  });
