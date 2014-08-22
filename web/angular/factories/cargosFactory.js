'use strict';

/* Filters */

angular.module('cargoApp.factories', [])
	.factory('cargosFactory', function($http, $filter) {
		
    var factory ={};

    factory.persons= [];
    factory.posts= [];
    factory.memberships= [];
    factory.organizations= [];

    factory.getFullPerson = function(id){
      var p = this.persons[id]; 
      if (!p.full){

        p.periods = this.getPeriods(p);
        p.summary = this.getSummary(p);
        p.full = true;
      }
      return p;
    };
    factory.getPeriods= function(person){
      for (var i = 0; i < person.memberships.length; i++) {
        var m = person.memberships[i];
        m.started = moment(m.start_date);
        m.finished = moment(m.end_date);
      };
      
      var expression = '-started';
      var a = $filter('orderBy')(person.memberships, expression, false);
      
      return {
          started: a[a.length-1].start_date,
          last: a[0].end_date
      };

    };
    factory.getSummary = function(person){
      var summary = 
      { 
          executives: 0,
          legislative:0,
          judiciary: 0, 
          elected : 0 ,
          notElected: 0,
          reElected: 0
      };
      for (var i = 0; i < person.memberships.length; i++) {

        var m = person.memberships[i];
        var cargo = this.getPost(m.post_id);
        if (cargo.cargoclase == 'Electivo'){
          summary.elected++;
        }else if (cargo.cargoclase == 'No Electivo'){
          summary.notElected++;
        }

        if (cargo.cargotipo == 'Ejecutivo'){
          summary.executives++;
        }else if (cargo.cargotipo == 'Legislativo'){
          summary.legislative++;
        }else if (cargo.cargotipo == 'Judicial'){
          summary.judiciary++;
        }


      };
        return summary;

    };

    factory.getPost = function(post_id){

      for (var i = 0; i < this.posts.length; i++) {
        var p = this.posts[i];
        if (p.id === post_id){
          return p;
        }
      }
      return undefined;
    }
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
            factory.persons = res.data;
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
              factory.memberships = res.data;
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
                  factory.organizations = res.data;
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




		return factory;
});
