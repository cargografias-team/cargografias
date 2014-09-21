'use strict';

/* Filters */

angular.module('cargoApp.factories')
	.factory('presetsFactory', function($http, $filter) {
		
    var factory ={};
    factory.presets = [];

    factory.presets.push({
    	nombre:'Presidentes de la Democracia',
    	valores:'nombre-2001-12-660-8-748-7-6-5',
    });
    factory.presets.push({
    	nombre:'Candidatos 2015',
    	valores:'nombre-2009-2416-9-2417-1613-10-2314',
    });
    factory.presets.push({
    	nombre:'Barones del Conurbano',
    	valores:'nombre-2009-1742-710-2090-2402-2418-770-2279',
    });
    factory.presets.push({
    	nombre:'Gobernadores (muy) Reelectos',
    	valores:'cargo-2009-1512-1240-2204-1904-1909',
    });

    return factory;
});