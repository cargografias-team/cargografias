'use strict';

/* Filters */

angular.module('cargoApp.factories', [])
  .factory('cargoLoaderFactory', function($http, $filter) {
    
    var f = {};

    f.load = function($scope,factory,callback){
          //TODO: MOVE TO A PROPER LOADER
            window.__cargos_data = {
                  personas:[],
                  cargosnominales:[],
                  partidos:[],
                  territorios: [],
                  cargos: []
                };
          
          $http.get('/js/datasets/pesopoder.json')
            .then(function(res){

              $scope.estado = "Representatividad";
              factory.weight = res.data;
            });

            $http.get('/js/datasets/gz/cargografias-persons-popit-dump.json')
               .then(function(res){
                $scope.estado = "Personas";
                factory.persons = res.data;
                  for (var i = 0; i < res.data.length; i++) {
                    var p = { id : res.data[i].id,
                        //nombre : res.data[i].naget firsme,
                        nombre : removeDiacritics(res.data[i].name),
                        apellido: '',
                        index: i,
                        image : res.data[i].images ?  res.data[i].images[0].url :'/img/person.png'    // intentando obtener la foto desde el popit!
                    };
                    res.data[i].image = p.image;
                    window.__cargos_data.personas.push(p)
                    factory.autoPersons.push(p);
                    factory.persons[i].initials = res.data[i].name.match(/[A-Z]/g).join('.') + ".";
                  };
              }).then(function(){

                $http.get('/js/datasets/gz/cargografias-memberships-popit-dump.json')
                 .then(function(res){
                  $scope.estado = "Puestos";
                  //factory.memberships = res.data;
                      for (var i = 0; i < res.data.length; i++) {
                          if (res.data[i].post_id){
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
                            window.__cargos_data.cargos.push(p);
                            factory.memberships.push(res.data[i]);
                          }

                      };


                }).then(function(){
                  $http.get('/js/datasets/gz/cargografias-organizations-popit-dump.json')
                    .then(function(res){
                      $scope.estado = "Organizaciones";
                      factory.organizations = res.data;
                      for (var i = 0; i < res.data.length; i++) {
                        var p = {
                            id : res.data[i].id,
                            nombre : res.data[i].name,
                            index: i,
                            nivel: res.data[i].name === 'Argentina' ? 'nacional' : 'provincial'
                          };
                        window.__cargos_data.territorios.push(p)

                      };

                  }).then(function(){

                      $http.get('/js/datasets/gz/cargografias-posts-popit-dump.json')
                      .then(function(res){
                        factory.posts = res.data;
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
        return f;
});