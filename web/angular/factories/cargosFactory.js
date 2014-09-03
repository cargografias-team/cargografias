'use strict';

/* Filters */

angular.module('cargoApp.factories', [])
	.factory('cargosFactory', function($http, $filter) {
		
    var factory ={};

    factory.persons= [];
    factory.posts= [];
    factory.memberships= [];
    factory.organizations= [];
    factory.weight= [];

    factory.getFullPerson = function(id){
      var p = this.persons[id]; 
      if (!p.full){

        p.periods = this.getPeriods(p);
        p.summary = this.getSummary(p);
        p.full = true;
        p.weight = this.setWeight(p)
      }
      return p;
    };
    factory.setWeight = function(person){
      for (var i = 0; i < person.memberships.length; i++) {
        var m = person.memberships[i];
        
        m.weight= 10;
        
        for (var j = 0; j < this.weight.length; j++) {
          
          var w = this.weight[j];

          if (w.cargo.toLowerCase() === m.cargonominal.toLowerCase()
            && w.poder.toLowerCase()  === m.post.cargotipo.toLowerCase()){
            m.weight = this.weight[j].representacion;
            m.hierarchy = this.weight[j].posicion;
          }
        }
        
      }

    }

    factory.getPoderometro = function(year, persons){

      var ejecutivo = { name:"Ejecutivo", children: []};
      var legistlativo = { name:"Legislativo", children: []};
      var judicial = { name:"Judicial", children: []};
      var data =  { name:"Argentina", children: []};
      


      for (var i = 0; i < persons.length; i++) {
          var p = persons[i];
          var activeMembershipForYear = factory.getActiveMembershipByYear(p,year);
          if (activeMembershipForYear){
            var item = {
              name: p.name,
              position: activeMembershipForYear.cargonominal,
              size: activeMembershipForYear.weight
            }

              if (activeMembershipForYear.post.cargotipo == 'Ejecutivo'){
                ejecutivo.children.push(item)
              }else if (activeMembershipForYear.post.cargotipo == 'Legislativo'){
                legistlativo.children.push(item)
              }else if (activeMembershipForYear.post.cargotipo == 'Judicial'){
                judicial.children.push(item)
              }
          }
      };
      if (ejecutivo.children.length > 0){ data.children.push(ejecutivo);} 
      if (legistlativo.children.length > 0){ data.children.push(legistlativo);} 
      if (judicial.children.length > 0){ data.children.push(judicial);} 
      

      return data;
    };

    factory.getPoderometroAnimado = function (year, persons){

      var poderometro =[];
      for (var i = 0; i < persons.length; i++) {
          var p = persons[i];
          var activeMembershipForYear = factory.getActiveMembershipByYear(p,year);
          if (activeMembershipForYear){
            var item = {
              cargo:activeMembershipForYear.post.cargotipo.toLowerCase(),
              name: p.name,
              position: activeMembershipForYear.cargonominal,
              size: activeMembershipForYear.weight
            }
            poderometro.push(item);
          }
          activeMembershipForYear= undefined;
      };
      return poderometro;
    };

    factory.getActiveMembershipByYear = function(p,year){
      var memberships = p.memberships;
          var activeMembershipForYear;
          for (var j = 0; j < memberships.length; j++) {
              var m = memberships[j];
              if (year >= m.started.year() && m.finished.year() >=year){
                activeMembershipForYear = m;
                break;
              }
          };
          return activeMembershipForYear;
    };
    factory.getJerarquimetro = function(year, persons){
      var positions =[];
      var ejecutivo = [];
      var legistlativo = [];
      var judicial = [];

      for (var i = 0; i < persons.length; i++) {
          var p = persons[i];
          var activeMembershipForYear = factory.getActiveMembershipByYear(p,year);
          if (activeMembershipForYear){
            var item = {
              cargo:activeMembershipForYear.post.cargotipo.toLowerCase(),
              name: p.name,
              position: activeMembershipForYear.cargonominal,
              level: activeMembershipForYear.hierarchy
            }
             if (activeMembershipForYear.post.cargotipo == 'Ejecutivo'){
                ejecutivo.push(item)
              }else if (activeMembershipForYear.post.cargotipo == 'Legislativo'){
                legistlativo.push(item)
              }else if (activeMembershipForYear.post.cargotipo == 'Judicial'){
                judicial.push(item)
              }
            
          }
          activeMembershipForYear= undefined;
      };
      positions.push(ejecutivo);
      positions.push(legistlativo);
      positions.push(judicial);

      var expression = '+level';
      for (var z = 0; z < positions.length; z++) {
        var p= positions[z]
        var treeData =[];
        var sorted = $filter('orderBy')(p, expression, false);
        
        
        for (var i = 0; i < sorted.length; i++) {
          var s = sorted[i];
          if (treeData.length === 0){
            treeData.push({
              name:s.name,
              parent:null,
              level: s.level,
              children:[]
            });
          }
          else {
            this.processItemsTree(treeData, s);
            
          } 
        }
        positions[z] = treeData;
      }
      
     
      return positions;
    };
    factory.processItemsTree = function(treeData, s){
        for (var j = 0; j < treeData.length; j++) {
              //Same Level, just push.
              if (s.level === treeData[j].level){
                treeData.push({
                  name:s.name,
                  parent:null,
                  level: s.level,
                  children:[]
                });
                break;
              }
              else if (treeData[j].children.length == 0){
                treeData[j].children.push({
                  name:s.name,
                  parent:null,
                  level: s.level,
                  children:[]
                });
                break;

              }
              else{ 
                factory.processItemsTree(treeData[j].children, s);
                
              }
            }
    }
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
        var cargo = m.post = this.getPost(m.post_id);
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
                    //nombre : res.data[i].name,
                    nombre : removeDiacritics(res.data[i].name),
                    apellido: '',
                    index: i,
                    image : res.data[i].image       // intentando obtener la foto desde el popit!
                };
                window.__cargos_data.personas.push(p)
                $scope.autoPersons.push(p);
              };
          }).then(function(){

            $http.get('/js/datasets/gz/cargografias-memberships-popit-dump.json')
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
              $http.get('/js/datasets/gz/cargografias-organizations-popit-dump.json')
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




		return factory;
});
