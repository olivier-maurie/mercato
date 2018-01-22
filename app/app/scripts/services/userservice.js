'use strict';

angular.module('mercatoApp')
  .service('userService', function ($http) {

    this.loginFunc = function(mail, password){
      var user = { 'mail': mail, 'password': password };
      return $http.post('http://127.0.0.1:8080/api/public/login', user)
        .then(function(response) { return response; })
        .catch(function(error) { console.log(error); });
    };

    this.registerFunc = function (mail, password, last_name, first_name, pseudo) {
      var user = { 'mail': mail, 'password': password, 'last_name': last_name, 'first_name': first_name, 'pseudo': pseudo};
      return $http.post('http://127.0.0.1:8080/api/public/users', user)
        .then(function(response) { return response; } )
        .catch(function(error) { return error; });
    };

    this.getPlayerByUser = function(){
      var user_id = JSON.parse(window.sessionStorage.getItem('user')).id;
      return $http.get('http://127.0.0.1:8080/api/public/users/' + user_id + '/players')
        .then(function(response) { return response.data; })
        .catch(function(error){ console.error(error); });
    };

    this.getUser = function(){
      var user_id = JSON.parse(window.sessionStorage.getItem('user')).id;
      return $http.get('http://127.0.0.1:8080/api/public/users/' + user_id)
        .then(function(response) { return response.data; })
        .catch(function(error){ console.error(error); });
    };

    this.putUser = function(pseudo, password){
      var user_id = JSON.parse(window.sessionStorage.getItem('user')).id;
      var user = { 'pseudo': pseudo, 'password': password};
      console.log(user_id, user);
      return $http.put('http://127.0.0.1:8080/api/public/users/' + user_id, user, {
        'Content-type': 'application/json',
      })
        .then(function(response) { console.log(response); })
        .catch(function(error) { console.error(error); });
    };
  });
