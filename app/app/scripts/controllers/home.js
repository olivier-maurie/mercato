'use strict';

angular.module('mercatoApp')
  .controller('HomeCtrl', function ($scope, $location, playerService, bidService, userService) {

    var user_id = JSON.parse(window.sessionStorage.getItem('user')).id;
    var nb_round = '';
    $scope.selectedPlayers = [];

    userService.getUser()
      .then(function(res){
        nb_round = res[0].nb_round;
        return nb_round;
      })

    playerService.getPlayerUnOwn()
      .then(function(response){
        $scope.playersList = response;
        return $scope.playersList;
      })
      .catch(function(error){ console.error(error); });

    $scope.selectPlayer = function(player){
      var index = $scope.playersList.indexOf(player);
      $scope.selectedPlayers.push(player);
      $scope.playersList.splice(index, 1);
    };

    $scope.removePlayer = function(player){
      var index = $scope.selectedPlayers.indexOf(player);
      console.log(player)
      $scope.playersList.push(player);
      console.log($scope.playersList);
      $scope.selectedPlayers.splice(index, 1);

      // console.log($scope.playersList);
    }
    //   $scope.selectPlayer = function(player){
    //   console.log(player.price);
    //     if(player.price > player.min_price){
    //       bidService.postBid(player.id, user_id, player.price, nb_round)
    //         .then(function(res) { console.log(res); })
    //         .catch(function(err) { console.error(err); });
    //     }else{
    //       alert('la valeur est inférieur au prix minimal');
    //     }
    // }
  });
