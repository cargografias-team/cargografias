'use strict';

/* Controllers */
angular.module('cargoApp.controllers')
  .controller('homeController', function($scope, $http, searchProvider) {
  	$scope.posts = ["Gobernador"];

    $scope.fetchPosts = function(typed){
        $scope.latestPost = searchProvider.fetchPositions(typed);
        $scope.latestPost.then(function(data){
        	console.log(data);
          $scope.posts = data.data.result;
        });
    }

    $http.get('/js/gz/cargografias-organizations-popit-dump.json')
       .then(function(res){
          console.log(res.data);
        });

  });
