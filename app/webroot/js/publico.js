var Config = {
	baseUrlGetCargos: '/api/get_cargos',
	baseUrlGetQuiz: '/api/get_quiz',
	baseUrlAPIHitos: '/api/hito/'
}

var PublicControl;

PublicControl = window.publicControl = window.publicControl || {};

PublicControl.hashVars = {};

PublicControl.init = function () {
    $(window).hashchange( PublicControl.changeUrlHandler );
};

PublicControl.hash = '';

PublicControl.changeUrlHandler = function() {
    var hash = location.hash.replace('#','');
	PublicControl.hash = hash;
    if(hash==''){
    	PublicControl.hashVars = {};
    	return false;
    }
    PublicControl.hashVars = $.parseParams(hash);

    $(PublicControl).trigger(
    	{
    		type:"HASH_CHANGE",
			vars:PublicControl.hashVars,
			hash:PublicControl.hash
		}
	);
}

$.parseParams = function(query) {
	var re = /([^&=]+)=?([^&]*)/g;
	var decodeRE = /\+/g;  // Regex for replacing addition symbol with a space
	var decode = function (str) {return decodeURIComponent( str.replace(decodeRE, " ") );};
    var params = {}, e;
    while ( e = re.exec(query) ) { 
        var k = decode( e[1] ), v = decode( e[2] );
        if (k.substring(k.length - 2) === '[]') {
            k = k.substring(0, k.length - 2);
            (params[k] || (params[k] = [])).push(v);
        }
        else params[k] = v;
    }
    return params;
};

$(document).ready(function(){
	PublicControl.init();
});