'use strict';

angular.module('mercatoApp')
  .controller('loginCtrl', function ($scope, $location, userService, userFactory) {

    console.log(window.sessionStorage.getItem('user'));
    console.log(userFactory.isAuth );

    if(userFactory.isAuth === true && window.sessionStorage.getItem('user') !== null){
      $location.path('/home');
    }else{
      $location.path('/login');
    }

    $scope.connect = function(){

      userService.loginFunc($scope.usermail, $scope.userpasswd)
        .then(function(response){
          sessionStorage.setItem('user', JSON.stringify(response.data[0]));
          console.log(response);
          $location.url('/home');
        })
        .catch(function(err){
          console.error(err);
        });
    };

    $scope.register = function () {

      userService.registerFunc($scope.email, $scope.pseudo, $scope.password, $scope.last_name, $scope.first_name)
        .then(function(response){
          console.log(response);
          $location.path('/login');
        })
        .catch(function(err){
          console.err(err);
        });
    };

  });
