'use strict';

angular.module('mercatoApp')
  .controller('HeaderCtrl', function ($scope, $location, userService, userFactory) {
    if(userFactory.isAuth !== true){
      if(window.sessionStorage.getItem('user') === null || window.sessionStorage.getItem('user') === 'undefined'){
        $location.path('/login');
      }
    }

    userService.getUser()
      .then(function(response) { $scope.user_coin = response[0].money; });
  });
