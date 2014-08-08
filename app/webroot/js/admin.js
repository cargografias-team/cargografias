$('document').ready(function(){
/*
	//Upload image
	if( $("form.imageForm").size()==1 ) {
		$('form.imageForm').ajaxForm({
			dataType:"json",
	        success: function(data) {
	        	$('#avatar-preview').attr('src',data.fileName);
	        	$('#imagen-seleccionada').val(data.fileName);
	        	$('#avatar-preview-loader').hide();
	        	$('#avatar-preview').show();
	        }
	    });
		
		$('#seleccionar-imagen').die('click').live('click', function(e){
			$('input[name="campoImagen"]').click();
			$('input[name="campoImagen"]').change(function(){
				$('#avatar-preview').attr('src','');
				$('#avatar-preview').hide();
	        	$('#avatar-preview-loader').show();
				$('form.imageForm').submit();
			});
		});
	}

	//Error en upload image
	if( $("#imagen-seleccionada.form-error").size()==1 ) {
		$(".selector-imagen").addClass('error');
		$(".selector-imagen .error-message").show();
	}

	if( $("#agencias-container").size()==1 ) {
		var temp = [];
		$.each(AGENCIAS_SELECCIONADAS, function(key, val) {
			temp.push(parseInt(val.agency_id));
		});
		AGENCIAS_SELECCIONADAS = temp;
		getAgencies();
	}

	if( $("#openpopup").size()==1 ) {
		$("#openpopup").colorbox({
			iframe:true, 
			width:"80%", 
			height:"80%",
			onCleanup:function(){ getAgencies(); }
		});
	}

});

function getAgencies(){
	var container = $('#agencias-container');
	container.html('');

	$.getJSON('/Ajax/getAgencies', function(data) {
		var items = [],item='',selected,check;

		  $.each(data, function(key, val) {

		  	item = $('<div/>').addClass('agency-item');

		  	check = $('<input/>').attr('type','checkbox')
		  	.attr('name','data[Agency][Agency][]')
		  	.val(val.Agency.agency_id).appendTo(item);

		  	if($.inArray(parseInt(val.Agency.agency_id), AGENCIAS_SELECCIONADAS)!==-1){
				check.attr('checked','checked');	
		  	}

		  	$('<img/>').attr('src',val.Agency.logo)
		  	.appendTo(item).appendTo(item);

		  	item.appendTo(container);

		  });
		
	});*/
});