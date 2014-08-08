var Trivia;

;(function(global, document, $, Conf, watch){

    "use strict";

    Trivia = global.linea = global.linea || {};

    Trivia.$container = $('#trivia');
    Trivia.$loader = $('#trivia-loader');
    Trivia.$startButton = $('.jugar_btn');
    Trivia.$presentacionContainer = $('#trivia-presentacion');
    Trivia.$triviaContainer = $('#trivia');
    Trivia.$triviaPlayground = $('#trivia-playground');
    Trivia.$triviaResultado = $('#trivia-resultado');
    Trivia.$finalContainer = Trivia.$triviaResultado.find('.final_container');
    Trivia.$triviaPanel = $('#trivia-panel');
    Trivia.$progress = $('#progress-bar');
    Trivia.$restartButton = $('#reiniciar_btn');
    Trivia.$twitterBtn = $('.twitter-share-button');

    //watch
    Trivia.level = 1;
    Trivia.points;
    Trivia.qty;
    Trivia.answers = [];

    //template
    $.template( "quiz_template", $('#quiz_template').html() );
    $.template( "bar_template", $('#bar_template').html() );
    $.template( "final_template", $('#final_template').html() );

    Trivia.watchCounters = function (prop, action, difference, oldvalue) {
        $('[data-bind="'+prop+'"]').html(difference);
    };

    Trivia.watchAnswers = function (prop, action, difference, oldvalue) {
        $.tmpl( "bar_template", difference[0] ).appendTo(Trivia.$progress);
    };

    Trivia.init = function () {
    	Trivia.$startButton.on('click',Trivia.start);
        Trivia.$restartButton.on('click',Trivia.restart);
        Trivia.$triviaContainer.on('click',".respuesta",Trivia.answer);
        Trivia.$triviaContainer.on('click',"#siguiente_btn",Trivia.nextButton);
        watch(Trivia, ["points","qty",,"level"], Trivia.watchCounters);
        watch(Trivia, "answers", Trivia.watchAnswers);
    };

    Trivia.restart = function () {
        document.location.reload()
    };

    Trivia.start = function () {
        Trivia.level = $(this).attr('rel');
        Trivia.points = 0;
        Trivia.qty = 0;
        Trivia.$presentacionContainer.hide();
        Trivia.$triviaPlayground.show();
        Trivia.next();
    };

    Trivia.answer = function () {
        if($(this).hasClass('disabled'))
            return;
        var rtaObj = $('.respuesta[data-valid="true"]');
        rtaObj.addClass('btn-success');
        $('.respuesta[data-valid="false"]').addClass('btn-danger');
        $('.respuesta').addClass('disabled');
        $('#siguiente_btn').removeClass('disabled');
        if(eval($(this).attr('data-valid'))){
            Trivia.points++;
        }
        var data = {
            correcto : eval($(this).attr('data-valid')),
            pregunta: $('#quiz_pregunta').html(),
            respuesta: rtaObj.html(),
            userRespuesta: $(this).html()
        };
        Trivia.answers.push(data);
    };

    Trivia.nextButton = function () {
        if($(this).hasClass('disabled'))
            return;
        Trivia.next();
    };

    Trivia.next = function () {
        if(Trivia.qty==10){
            Trivia.end();
            return;
        }
        Trivia.qty++;
        Trivia.getQuiz();
    };

    Trivia.end = function () {
        Trivia.$triviaContainer.hide();
        Trivia.$triviaPanel.hide();
        Trivia.$triviaResultado.show();
        Trivia.$twitterBtn.attr('data-text','He conseguido '+Trivia.points+' puntos en la Trivia de historia política Argentina. Inténtalo en http://cargo.palamago.com.ar/cargografia/trivia')
        Trivia.$finalContainer.html($.tmpl( "final_template", {respuestas:Trivia.answers} ) );
        $('.indicador-respuesta').tooltip();

        //Renderiza Twitter btn
        !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
    };

    Trivia.getQuiz = function () {
	    Trivia.$loader.show();
	    Trivia.$container.html('');
        var d = new Date().getTime();
        $.getJSON(Conf.baseUrlGetQuiz+'/'+Trivia.level+'?d='+d, function(data) {
            Trivia.$container.html($.tmpl( "quiz_template", data ));
            Trivia.$loader.hide();
        });
    };

})(window, document, jQuery, Config, watch);

window.onload = function() {
    Trivia.init(); 
}