'use strict';

/* Filters */

angular.module('cargoApp.factories')
	.factory('cargosFactory', function($http, $filter, cargoLoaderFactory) {
		
    var factory ={};

    factory.persons= [];
    factory.posts= [];
    factory.memberships= [];
    factory.organizations= [];
    factory.weight= [];
    factory.autoPersons=[];
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
    factory.getHallOfShame =function(activePersons){

      var hall = [];
      if (activePersons.length > 3){
        var shames = [];
        var simpleNumber = function(person){
          var s = this;
          return person.summary[s.value];
        }
        //TODO: tenemos que pasarlo a una lista
        shames.push({
          name: 'Cargos Electos',
          uom:'cargos',
          expression:'-summary.elected',
          value: 'elected',
          getNumber: simpleNumber
        });
        shames.push({
          name: 'Cargos Ejecutivos',
          uom:'cargos',
          expression:'-summary.executives',
          value: 'executives',
          getNumber: simpleNumber
        });
        shames.push({
          name: 'Cargos Legislativos',
          uom:'cargos',
          expression:'-summary.legislative',
          value: 'legislative',
          getNumber: simpleNumber
        });
        shames.push({
          name: 'Cargos Judiciales',
          expression:'-summary.judiciary',
          value: 'judiciary',
          getNumber: simpleNumber
        });
        shames.push({
          name: 'Reelecciones',
          uom:'relecciones',
          expression:'-summary.reElected',
          value: 'reElected',
          getNumber: simpleNumber
        });
        shames.push({
          name: 'Cargos No electivos',
          uom:'cargos',
          expression:'-summary.notElected',
          value: 'notElected',
          getNumber: simpleNumber
        });
        shames.push({
          name: 'Cargos Distintos',
          uom:'cargos',
          expression:'-memberships.length',
          getNumber: function(person){
            return person.memberships.length;
          }
        });
        shames.push({
          name: 'Años en cargos',
          uom:'años',
          expression:'-periods.yearsCharges',
          getNumber: function(person){
            return person.periods.yearsCharges;
          }
        });
        shames.push({
          name: 'Años en política',
          uom:'años',
          expression:'-periods.yearsPolitics',
          getNumber: function(person){
            return person.periods.yearsPolitics;
          }
        });



              // <td>{{active.memberships.length}}</td>
              // <td>{{active.periods.started}}</td>
              // <td>{{active.periods.last}}</td>


        for (var i = 0; i < shames.length; i++) {
          var s = shames[i];
          var order = $filter('orderBy')(activePersons, s.expression, false);
          var first = order[0];
          if (s.getNumber(first) > 0){
            hall.push({
                titulo: s.name,
                nombre: first.name, 
                medida: s.uom,
                cantidad: s.getNumber(first),
              });
          }
        };
      }
      return hall; 
    };
    factory.setWeight = function(person){
      for (var i = 0; i < person.memberships.length; i++) {
        var m = person.memberships[i];
        if (m.cargonominal)        
          for (var j = 0; j < this.weight.length; j++) {
            
            var w = this.weight[j];

            if (w.cargo.toLowerCase() === m.cargonominal.toLowerCase()
              && w.poder.toLowerCase()  === m.post.cargotipo.toLowerCase()){
              m.weight = this.weight[j].representacion;
              m.hierarchy = this.weight[j].posicion;
            }
          } 
        }
    };

    factory.getAutoPersons = function(q){
      return $filter('filter')(this.autoPersons, {nombre: q}, false);
    };
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
          var item = {
              cargo:"Sin cargo en " + year,
              name: p.name,
              initials: p.initials,
              classification: "sin cargo",
              district: "Sin cargo en " + year,
              position: "",
              size: 5,
            };
          if (activeMembershipForYear){
            item = {
              cargo:activeMembershipForYear.post.cargotipo.toLowerCase(),
              name: p.name,
              initials: p.initials,
              classification: activeMembershipForYear.organization.classification,
              district: activeMembershipForYear.organization.name,
              position: activeMembershipForYear.cargonominal,
              size: activeMembershipForYear.weight
            }
          }
          poderometro.push(item);
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
                if (!m.post){
                  m.post = this.getPost(m.post_id);
                }
                if (!m.organization){
                  m.organization = this.getOrganization(m.organization_id); 
                }
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
      var yearsSum = 0;
      for (var i = 0; i < person.memberships.length; i++) {
        var m = person.memberships[i];
        m.started = moment(m.start_date);
        m.finished = moment(m.end_date);
        m.years = m.finished.diff(m.started, 'years', true);
        m.years = parseFloat(m.years.toFixed(2));
        yearsSum+= m.years;
      };
      
      var expression = '-started';
      var a = $filter('orderBy')(person.memberships, expression, false);
      var resume = {
          started: moment(a[a.length-1].start_date),
          last: a[0].end_date ? moment(a[0].end_date) : undefined,
          yearsCharges: parseFloat(yearsSum.toFixed(2))
      };

      var now =moment(); 
        var years = 0;
        if (resume.last){
          //Si el periodo termina despues.
          if (now.diff(resume.last, 'milliseconds', true) > 0){
               years =resume.last.diff(resume.started, 'years', true);
          }
          else {
            years = now.diff(resume.started , 'years', true);
          }
        }
        else {
          years = now.diff(resume.started , 'years', true);

        }
        //Si el periodo ya termino.        
        resume.yearsPolitics = parseFloat(years.toFixed(2));



      return resume ;


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
        if (cargo){
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
    factory.getOrganization = function(organization_id){

      for (var i = 0; i < this.organizations.length; i++) {
        var o = this.organizations[i];
        if (o.id === organization_id){
          return o;
        }
      }
      return undefined;
    }
    factory.load = function ($scope,callback, $rootScope) {
      cargoLoaderFactory.load($scope,factory,callback, $rootScope);
      
    }




		return factory;
});
