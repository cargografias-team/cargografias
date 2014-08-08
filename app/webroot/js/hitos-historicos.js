var Hitos;

;(function(global, document, $, Conf, watch, PublicControl){

    "use strict";

    Hitos = global.hitos = global.hitos || {};

    Hitos.$container = $('#cargos');

    Hitos.$loader1 = $('#hitos-loader1');

    Hitos.$loader2 = $('#hitos-loader2');

    Hitos.$select = $('#hito');

    Hitos.$hitoContainer = $('#hito-container');

    Hitos.$paginateContainer = $('#paginate-container');

    Hitos.$expand = $('#expand-hitos-container');

    $.template( "hito_template", $('#hito_template').html() );

    $.template( "paginate_template", $('#paginate_template').html() );

    Hitos.chart = false;

    Hitos.init = function () {
        $("#paginate-container").delegate('.load-new-page', "click",Hitos.nextPageHandler);
        Hitos.$select.on('change', Hitos.changeSelectHandler);
        $(PublicControl).bind('HASH_CHANGE',Hitos.changeUrlHandler);
        Hitos.checkInitialUrl();
    };

    Hitos.checkInitialUrl = function() {
        var hash = location.hash;
        if(hash=='')
            return false;
        Hitos.$select.val(hash.split('=')[1]);
        Hitos.$select.trigger("liszt:updated");
        PublicControl.changeUrlHandler();
    }

    Hitos.changeUrlHandler = function(e) {
        Hitos.$loader1.show();
        Hitos.$loader2.show();
        Hitos.$hitoContainer.html('');

        Hitos.$hitoContainer.collapse('hide');

        var today = new Date();
        today = today.getFullYear()+'-'+(("0" + (today.getMonth() + 1)).slice(-2))+'-'+(("0" + today.getDate()).slice(-2));

        var finalUrl = Conf.baseUrlAPIHitos + e.vars.hito;

        if(e.vars.hito == today){
            var todayData = {
                titulo: "ACTUALIDAD",
                fecha: today,
                detalle: "Noticias de hoy..."
            };
            Hitos.$hitoContainer.html($.tmpl( "hito_template", todayData));
            Hitos.$loader1.hide();
            Hitos.$expand.show();
        } else {
            $.getJSON(finalUrl, function(data) {
                Hitos.$loader1.hide();
                Hitos.$hitoContainer.html($.tmpl( "hito_template", data));
                Hitos.$expand.show();
            });
        }



        finalUrl = Conf.baseUrlGetCargos + '?' + e.hash;
        $.getJSON(finalUrl, function(data) {
            Hitos.$loader2.hide();

            if(data.data.length==0){
                Hitos.preFilterData({data:[]});
            }else{
                Hitos.preFilterData(data);
            }

            Hitos.$paginateContainer.html($.tmpl( "paginate_template", data.metadata));
        });

    }

    Hitos.nextPageHandler = function(e) {
        Hitos.loadNewPage($(this).attr('rel'));
    }

    Hitos.loadNewPage = function(page) {
        Hitos.$loader2.show();
        var finalUrl = Conf.baseUrlGetCargos + window.location.hash.replace('#','?') + '&pag=' + page;
        $.getJSON(finalUrl, function(data) {
            Hitos.$loader2.hide();

            Hitos.chart.addPage(data.data);

            Hitos.$paginateContainer.html($.tmpl( "paginate_template", data.metadata));

        });
    }

    Hitos.changeSelectHandler = function(e) {
        if(this.value=='')
            return false;
        window.location = '#hito='+this.value;
    }

    Hitos.preFilterData = function(data) { 
        Hitos.render(data);        
    };

    Hitos.render = function (data) { 
        var options = {
          width : $('.container').width(),
          hitos: [{
            date: Hitos.$select.val(),
            desc: Hitos.$select.find("option:selected").text()
          }]
        };
        if(Hitos.chart){
            Hitos.chart.update(data.data,options);
        }else{
            Hitos.chart = d3.orgChart.init('cargos',data.data,options);
        }
    };

})(window, document,jQuery, Config, watch, PublicControl);

window.onload = function() {
    Hitos.init(); 
}