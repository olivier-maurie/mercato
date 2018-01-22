'use strict';

angular.module('mercatoApp')
  .service('playerService', function ($http) {
    this.getPlayerUnOwn = function(){
      return $http.get('http://127.0.0.1:8080/api/public/players/free')
        .then(function(response) { return response.data; })
        .catch(function(error){ console.error(error); });
    };
  });
