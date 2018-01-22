'use strict';

angular.module('mercatoApp')
  .factory('userFactory', function () {
    var User = {};
    if( window.sessionStorage.getItem('user') === null ){
      User.isAuth = false;
      User.data = '';
      return User;
    }else{
      User.isAuth = true;
      User.data = JSON.parse(window.sessionStorage.getItem('data'));
      return User;
    }
  });
