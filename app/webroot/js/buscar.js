var Buscar;

;(function(global, document, $, Conf, watch){

    "use strict";

    Buscar = global.buscar = global.buscar || {};

    Buscar.$formBuscar = $('#BuscarFormModelBuscarForm');

    Buscar.idEntidad = Buscar.$formBuscar.attr('data-key');

    Buscar.storageKey = 'selectedItems'+   Buscar.idEntidad;

    Buscar.$formComparar = $('#form-comparar-ids');

    Buscar.$selectedContainer = Buscar.$formComparar.find('tbody');

    Buscar.$limpiarButton = $('#limpiar-buscar');

    Buscar.selectedItems = {};

	Buscar.$searchInput = $("#BuscarFormModelSearch");
	
    //template
    $.template( "selected_template", $('#selected_template').html() );

    Buscar.init = function () {

        Buscar.$formComparar.submit(Buscar.submitComparar);

        watch(Buscar, "selectedItems", Buscar.watchItems, 0, true);

		// Agregar items para comparar
        $(document).on('click', '.agregar-buscar', Buscar.agregarItem);
		// Limpiar items para comparar
        Buscar.$limpiarButton.on('click',Buscar.clearItems);
		// Eliminar items de comparar
        Buscar.$selectedContainer.on("click", ".eliminar-buscar", Buscar.borrarItem);

        $("#comparacion-reports").on("click", ".eliminar-buscar", Buscar.borrarItem);

        Buscar.loadStorage();

        // Autocomplete del territorio
		$('#BuscarFormModelTerritorio').autocomplete({
			source: window.nombresTerritorios,
			minLength: 0,
    	});    

        // Autocomplete del partido
		$('#BuscarFormModelPartido').autocomplete({
			source: window.nombresPartidos,
			minLength: 0,
    	});    

        // Autocomplete del cargo
		$('#BuscarFormModelCargo').autocomplete({
			source: $('#BuscarFormModelCargo').data('url-autocomple'),
			minLength: 0,
    	});    

    	// Borrar un reporte del accordion
    	$('#persona-reports').on("click", ".accordion-close", Buscar.closeAccordion);
		// Lazy loading de los resultados de la busqueda
		$('#search-resultados').bind("scroll", Buscar.onResultsSroll);

		$('#limpiar-buscar-filtro').click(Buscar.limpiarFiltroBusqueda);

        Buscar.inicializarTimeline();
    };

    Buscar.loadStorage = function () {
       	var url = window.location.pathname;
		var matches = url.match(/personas\/buscar\/(.*)/);
		if( matches ){
	       	var ids = matches[1].split('/');
			$.each(ids, function( index, id ) {
	  			Buscar.selectedItems[id] = {persona_id: id};
			});

		} else {
		    if( !(localStorage && localStorage[Buscar.storageKey]) ){
	            var defaultData = {"222":{"apellido":"De la Rúa","nombre":"Fernando","persona_id":"222","nombre_completo":"Fernando De la Rúa"},"230":{"apellido":"Rodríguez Saá","nombre":"Adolfo","persona_id":"230","nombre_completo":"Adolfo Rodríguez Saá"},"392":{"apellido":"Duhalde","nombre":"Eduardo Alberto","persona_id":"392","nombre_completo":"Eduardo Alberto Duhalde"},"487":{"apellido":"Menem","nombre":"Carlos Saul","persona_id":"487","nombre_completo":"Carlos Saul Menem"},"811":{"apellido":"Kirchner","nombre":"Néstor Carlos","persona_id":"811","nombre_completo":"Néstor Carlos Kirchner"},"930":{"apellido":"Fernández","nombre":"Cristina","persona_id":"930","nombre_completo":"Cristina Fernández"}}
	            $.each(defaultData,function(i,e){
	                Buscar.selectedItems[i] = e;
	            });                
		    }
		}
    };

    Buscar.submitComparar = function () {
        var ids='',$inputs = $(this).find('input');
        if($inputs.size()>=0){
            $inputs.each(
                function(i,e){
                    ids += '/' + e.value; 
                });
                
			$.get(Buscar.$formComparar.attr('data-url') + ids, function( data ) {
  				eval(data);
			});

        }
        return false;
    };

    Buscar.actualizarTimeline = function(){
        var idsArr = _.map($("#table-selected").find('input[type="hidden"]'), function(it){return it.value;})
        if(idsArr.length===0){ idsArr = [-1]; } //Valor para que no ponga nada en el timeline
        timelineParams.filtro.idPersonas = idsArr;
        Buscar.cargoTimeline.update(timelineParams);
    };

    Buscar.agregarItem = function (e) {
        var info = $(this).parent().find('input').val();
            info = eval("(" +info+")");

      try{
        ga('send', 'event', 'agregarTimeline', 
            info.persona_id + '|' + info.apellido, 
            timelineParams.filtro.idPersonas.slice().sort(function(a,b){ return parseInt(a,10) - parseInt(b,10); }).join("|")
        );
      }catch(ex){}

        Buscar.selectedItems[info[[Buscar.idEntidad+'_id']]] = info;
        
        return false;
    };

    Buscar.borrarItem = function (e) {
        var id = $(this).attr('data-id');

      try{
        ga('send', 'event', 'borrarTimeline', 
            id, 
            timelineParams.filtro.idPersonas.slice().sort(function(a,b){ return parseInt(a,10) - parseInt(b,10); }).join("|")
        );
      }catch(ex){}

        unwatch(Buscar.selectedItems, id, Buscar.watchItems);
        delete Buscar.selectedItems[id];
        
        return false;
    };

    Buscar.watchItems = function (prop, action, difference, oldvalue) {
        WatchJS.noMore = true;
        if(action == 'differentattr' && difference.added.length > 0 ){
            $.each(difference.added,function(i,e){
                $.tmpl( "selected_template", Buscar.selectedItems[difference.added[i]] ).appendTo(Buscar.$selectedContainer);
            });
        }

        if(action == 'differentattr' && difference.removed.length > 0 ){
             $.each(difference.removed,function(i,e){
                $('.selected-'+difference.removed[0]).remove();
            });
        }

        if(Buscar.$formComparar.find('tr.seleted-item').size()==0){
            Buscar.$formComparar.find('button').addClass('disabled');
            Buscar.$limpiarButton.addClass('disabled');
        }else{
            Buscar.$formComparar.find('button').removeClass('disabled');
            Buscar.$limpiarButton.removeClass('disabled');
        }

        localStorage[Buscar.storageKey] = JSON.stringify(Buscar.selectedItems);
        Buscar.actualizarTimeline();

        // actualizar el reporte de comparacion
        Buscar.$formComparar.submit();
        
        //actualizar la barra de navegacion
        Buscar.updateNavigationUrl();
    };

    Buscar.clearItems = function (prop, action, difference, oldvalue) {
        Buscar.selectedItems = {};
        Buscar.$selectedContainer.empty();
        localStorage.selectedItems = null;
    };

	Buscar.closeAccordion = function (e) {
		$(this).parents('.accordion-group').remove();
		return false;
    };

	Buscar.onResultsSroll = function() {
	    if( $(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight ){
			$('#resultados-paging .next-page').click();
	   	}
	};

	Buscar.limpiarFiltroBusqueda = function(){
		Buscar.$searchInput.val('');
		$('#BuscarFormModelTerritorio').val('');
		$('#BuscarFormModelPartido').val('');
		$('#BuscarFormModelCargo').val('');
		Buscar.$formBuscar.submit();
	}

	Buscar.updateNavigationUrl = function(){
		var ids = '';
		for(var id in Buscar.selectedItems){
			ids += '/' + id;
		}
		history.pushState({}, "", '/personas/buscar' + ids);
	}

    var idPersonas = [9999];
    var timelineParams = {
        filtro: { idPersonas: idPersonas },
        mostrarPor: "cargo",
    };

    Buscar.inicializarTimeline = function(){
    
        var cargoTimeline = new CargografiasTimeline({
            containerEl : document.getElementById('timelineContainer'),
            mostrarPor: "cargo",
            filtro:{ 
                idPersonas: idPersonas 
            }
        });

        Buscar.cargoTimeline = cargoTimeline;

        $('#ordenNombre').click(function(event){
            event.preventDefault();
            $(this).parent().addClass('active').siblings().removeClass('active');
            setTimeout(function(){                
                timelineParams.mostrarPor = "nombre";
                cargoTimeline.update(timelineParams);
            },50);
        });

        $('#ordenCargo').click(function(event){
            event.preventDefault();
            $(this).parent().addClass('active').siblings().removeClass('active');
            setTimeout(function(){
                timelineParams.mostrarPor = "cargo";
                cargoTimeline.update(timelineParams);                
            },50);
        });
    
// 

        (function(){
        var btnBuscar = $("#buscar-submit").hide();

        $(".search-row input[type=text]").on('keyup change autocompleteselect', _.debounce(function(){
        btnBuscar.click();  
        },200));

        })();


    }

})(window, document, jQuery, Config, watch);

window.onload = function() {
    Buscar.init(); 
}
