'use strict';

/* Filters */

angular.module('cargoApp.factories')
	.factory('presetsFactory', function($http, $filter) {
		
    var factory ={};
    factory.presets = [];

    factory.presets.push({
    	nombre:'Presidentes de la Democracia',
    	valores:'nombre-2001-12-8-7-6-5-685',
    });
    factory.presets.push({
    	nombre:'Candidatos 2015',
    	valores:'nombre-2009-2416-9-2417-10-2314-664-1615-663',
    });
    factory.presets.push({
    	nombre:'Barones del Conurbano',
    	valores:'nombre-2009-1742-710-2090-2402-2418-770-2279',
    });
    factory.presets.push({
    	nombre:'Gobernadores (muy) Reelectos',
    	valores:'nombre-1992-2204-22-1514-1871-1906-21',
    });

    return factory;
});