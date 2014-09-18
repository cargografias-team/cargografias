'use strict';

/* Controllers */
angular.module('cargoApp.controllers')
  .controller('homeController', function($rootScope,$scope,cargosFactory, $filter, $routeParams, $location, $route) {
  	$scope.ready= false;
  	
    $scope.autoPersons = [];
  	


  	$scope.activePersons = [];
    $scope.estado = "";
  	$rootScope.observers =[];
    $rootScope.yearObserver =[];
    var parsedParams;

    var processParameters = function(params){
        parsedParams = params.split('-');
        $scope.filterLinea = parsedParams.shift();
        $scope.poderometroYear = $scope.activeYear = parseInt(parsedParams.shift());
    }
    $rootScope.jerarquimetroObserver =[];
    $scope.filterLinea ="cargo";

    //Load initial ids from the url
    if($routeParams.ids){
     processParameters($routeParams.ids);
    }


  $scope.$watch('activeYear', function(){
    updateTheUrl();
  });


  $scope.load = function(params){
      
      processParameters(params);
      if(parsedParams){
        angular.forEach(parsedParams, function(id){
          $scope.add(cargosFactory.autoPersons[id], id);
        });
      }

  }



  var onDataLoaded = function(){
    
     $scope.estado = "Motor de Visualizacion";
      for (var i = 0; i < $rootScope.observers.length; i++) {
        var observer = $rootScope.observers[i];
        observer();
      };
      $scope.estado = "Listo!";
      $scope.ready= true;

      //Load initial ids from the url
      if(parsedParams){
        angular.forEach(parsedParams, function(id){
          $scope.add(cargosFactory.autoPersons[id], id);
        });
      }

      $scope.redrawPoderometro();

  };
  
  $scope.redrawPoderometro = function(){
    for (var i = 0; i < $rootScope.yearObserver.length; i++) {
      var observer = $rootScope.yearObserver[i];
      var poderometro = cargosFactory.getPoderometroAnimado($scope.poderometroYear, $scope.activePersons);
      observer(poderometro);
    };
    for (var i = 0; i < $rootScope.jerarquimetroObserver.length; i++) {
      var observer = $rootScope.jerarquimetroObserver[i];
      var jerarquimetro = cargosFactory.getJerarquimetro($scope.poderometroYear, $scope.activePersons);
      observer(jerarquimetro);
    };
    

  }


  $scope.filterAutoPersons = function(q){
    if (q.length > 4){
      $scope.autoPersons =cargosFactory.getAutoPersons(q);
    }
  };
  
  $scope.clearFilter = function(){
      
     //HACK: why?????????
     $("#nombre").val('');
     $scope.autoPersons = [];
  };

  $scope.clearResults= function(){
     $("#nombre").val('');
     $scope.autoPersons = [];
  }

  cargosFactory.load($scope,onDataLoaded);

  var lastRoute = $route.current;
  $scope.$on('$locationChangeSuccess', function(event) {
      // If same controller, then ignore the route change.
      if(lastRoute.controller == $route.current.controller) {
        $route.current = lastRoute;
      }
  });

  function updateTheUrl(){
      //Update the URL
      $location.path("/" + $scope.filterLinea  + "-" + $scope.activeYear + "-" + $scope.activePersons.map(function(p){ return p.autoPersona.index }).join('-'));
  }


    $scope.add = function(autoPersona, id){
      if (autoPersona.agregada) return ;
      
      $scope.autocomplete = " ";
      autoPersona.agregada = true;
      autoPersona.styles = "badge-selected"
    	
      var person = cargosFactory.getFullPerson(id);
      person.autoPersona = autoPersona;
      $scope.activePersons.push(person);


    	var idPersonas = cargoTimeline.options.filtro.idPersonas;
    	idPersonas.push(person.id);
			var timelineParams = {
			   filtro: { idPersonas: idPersonas },
			   mostrarPor: $scope.filterLinea,
			};
    	window.cargoTimeline.update(timelineParams);
    
      updateTheUrl();
      $scope.redrawPoderometro();

    };

    $scope.orderLine =function(order){
      $scope.filterLinea = order;
      var idPersonas = cargoTimeline.options.filtro.idPersonas;
      var timelineParams = {
         filtro: { idPersonas: idPersonas },
         mostrarPor: $scope.filterLinea,
      };
      window.cargoTimeline.update(timelineParams);
      updateTheUrl();


    }

    $scope.remove = function(person){
    	var indexOf = $scope.activePersons.indexOf(person);
    	if (indexOf > -1){
    		 $scope.activePersons.splice(indexOf, 1);
    	}
      person.autoPersona.agregada = false;
      person.autoPersona.styles = "";
    	indexOf = cargoTimeline.options.filtro.idPersonas.indexOf(person.id);
    	if (indexOf > -1){
    		 cargoTimeline.options.filtro.idPersonas.splice(indexOf, 1);
    	}
    	var idPersonas = cargoTimeline.options.filtro.idPersonas;
			var timelineParams = {
			   filtro: { idPersonas: idPersonas },
			   mostrarPor: $scope.filterLinea,
			};
    	window.cargoTimeline.update(timelineParams);

      updateTheUrl();
      $scope.redrawPoderometro();

    };

    $scope.clearAll = function(){
      for (var i = 0; i < $scope.activePersons.length; i++) {
        $scope.activePersons[i].autoPersona.agregada = false;
      };
      $scope.activePersons = [];
      var idPersonas = [];
      var timelineParams = {
         filtro: { idPersonas: idPersonas },
         mostrarPor: $scope.filterLinea,
      };
      window.cargoTimeline.update(timelineParams);

      updateTheUrl();

    }





  });

