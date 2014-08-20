'use strict';

/* Filters */

angular.module('cargoApp.factories', [])
	.factory('searchProvider', function($http) {
		var url = 'http://cargografias.popit.mysociety.org/api/v0.1/search/posts?q=role:';
	  	var fetchPositions = function(q) {
	  		url += q;
	  		return $http.jsonp(url + '&callback=JSON_CALLBACK', {cache:true})
	  		.then(function(data) {
	  			return data;
	  		});
	  	};
	  	var factory = {
	    	fetchPositions : fetchPositions
	   	}
		return factory;
});
