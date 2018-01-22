'use strict';

angular.module('mercatoApp')
  .service('bidService', function ($http) {
    this.postBid = function(id_player, id_user, price, nb_round){
      var bid = {
        'id_player': id_player,
        'id_user': id_user,
        'price': price,
        'nb_round': nb_round
      };
      return $http.post('http://127.0.0.1:8080/api/public/bids', bid, {
        'Content-type': 'application/json',
      })
        .then(function(res) { console.log(res); })
        .catch(function(err) { console.log(err); });
    };
  });
