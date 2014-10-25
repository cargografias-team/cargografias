'use strict';

angular.module('cargoApp.controllers', []);
angular.module('cargoApp.directives', []);

// Declare app level module which depends on filters, and services
angular.module('cargoApp', [
  'ngRoute',
  'cargoApp.filters',
  'cargoApp.services',
  'cargoApp.factories',
  'cargoApp.directives',
  'cargoApp.controllers',
  'angularMoment',
  'ngCookies'
]).
config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/:ids?', {templateUrl: '/angular/partials/main.html', controller: 'homeController'});
  $routeProvider.otherwise({redirectTo: '/'});
}]);
