'use strict';

/* Filters */

angular.module('cargoApp.factories', [])
	.factory('cargosFactory', function($http) {
		
    var factory ={};


    factory.load = function($scope,callback){
      //TODO: MOVE TO A PROPER LOADER
        window.__cargos_data = {
              personas:[],
              cargosnominales:[],
              partidos:[],
              territorios: [],
              cargos: []
            };
      


        $http.get('/js/gz/cargografias-persons-popit-dump.json')
           .then(function(res){
            $scope.estado = "Personas";
            $scope.persons = res.data;
              for (var i = 0; i < res.data.length; i++) {
                var p = { id : res.data[i].id,
                    nombre : res.data[i].name,
                    apellido: '',
                    index: i
                };
                window.__cargos_data.personas.push(p)
                $scope.autoPersons.push(p);
              };
          }).then(function(){

            $http.get('/js/gz/cargografias-memberships-popit-dump.json')
             .then(function(res){
              $scope.estado = "Puestos";
              $scope.memberships = res.data;
                  for (var i = 0; i < res.data.length; i++) {
                    var p = {
                        id : res.data[i].id,
                        index: i,
                        cargo_nominal_id: res.data[i].post_id,
                        fechafin: res.data[i].end_date,
                        fechainicio: res.data[i].start_date,
                        partido_id: null,
                        persona_id: res.data[i].person_id,
                        territorio_id: res.data[i].organization_id,
                    };
                    window.__cargos_data.cargos.push(p)

                  };


            }).then(function(){
              $http.get('/js/gz/cargografias-organizations-popit-dump.json')
                .then(function(res){
                  $scope.estado = "Organizaciones";
                  $scope.organizations = res.data;
                  for (var i = 0; i < res.data.length; i++) {
                    var p = {
                        id : res.data[i].id,
                        nombre : res.data[i].name,
                        index: i
                      };
                    window.__cargos_data.territorios.push(p)

                  };

              }).then(function(){

                  $http.get('/js/gz/cargografias-posts-popit-dump.json')
                  .then(function(res){
                    $scope.posts = res.data;
                    $scope.estado = "Partidos";
                    for (var i = 0; i < res.data.length; i++) {
                      var p = {
                          id : res.data[i].id,
                          nombre : res.data[i].cargonominal,
                          label: res.data[i].label,
                          index: i,
                          duracion: res.data[i].duracioncargo,
                          clase: res.data[i].cargoclase,
                          tipo: res.data[i].cargotipo
                      };
                      window.__cargos_data.cargosnominales.push(p);
                    };

                }).then(callback);
              });
            });
         });










    }




		return factory;
});
