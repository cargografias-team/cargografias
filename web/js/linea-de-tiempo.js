var Linea;

;(function(global, document, $, Conf, d3){

    "use strict";

    Linea = global.linea = global.linea || {};

    Linea.$container = $('#timeline');

    Linea.$loader = $('#linea-loader');;

    Linea.$refreshButton = $('#refresh_btn');

    Linea.$clearButton = $('#clear_btn');

    Linea.filters = {
    	$cargo: $('#cargo'),
    	$partido: $('#partido'),
    	$territorio: $('#territorio')
    };

    Linea.init = function () {
    	Linea.$refreshButton.on('click',Linea.refresh);
    	Linea.$clearButton.on('click',Linea.clear);
    };

    Linea.clear = function () {
    	$.each(Linea.filters, function(i,e){
    		if(e.val()==='')
    			return;
    		e.val('');
    		e.trigger("liszt:updated");
    	});
    };

    Linea.refresh = function () {
	    Linea.$loader.show();
	    Linea.$container.html('');
    	var qs = [];
    	$.each(Linea.filters, function(i,e){
    		if(e.val()==='')
    			return;
    		qs.push(e.attr('id')+'='+e.val());
    	});

        $.getJSON(Conf.baseUrlGetCargos+'?'+qs.join('&'), function(data) {
            Linea.preFilterData(data);
        });
    };

    Linea.preFilterData = function (data) {
    	Linea.render(data.data);
    };

    Linea.render = function (data) {
        Linea.$loader.hide();
        d3.cargoChart('timeline',data);
   	    //Linea.$container.html(JSON.stringify(data));
    };
 
})(window, document, jQuery, Config, d3);

window.onload = function() {
    Linea.init(); 
}