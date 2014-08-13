'use strict';

/* Controllers */
angular.module('cargoApp.controllers')
  .controller('homeController', function($scope, $http,$filter) {
  	$scope.organizations = [];
  	$scope.rawOrganizations = [];

    $http.get('/js/gz/cargografias-organizations-popit-dump.json')
       .then(function(res){
       	$scope.organizations = res.data;
	       	for (var i = 0; i < res.data.length; i++) {
	       		$scope.rawOrganizations.push({
		       			id : res.data[i].id,
		       			name : res.data[i].name
	       			});
	       	};
        });

    $scope.handleSelection = function(id){
    	console.log(id);
    	$scope.selectedOrganization =$scope.organizations[id];
    	console.log($scope.selectedOrganization);
    }
  });

