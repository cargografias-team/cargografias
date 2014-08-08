<!-- esto esta en seleccionados.ctp -->
	<div id="selected-buscar">
		<form id="form-comparar-ids" class="form-search" data-url="<?php echo Router::url(array('action'=>'comparar'));?>" >
			<table id="table-selected" class="table table-striped">
	      		<tbody>
	
	      		</tbody>
	      	</table>
		  <button type="submit" class="btn btn-warning disabled hidden">Comparar</button>
		  <a id="limpiar-buscar" type="submit" class="btn btn-danger disabled">Limpiar</a>
		</form>
	</div>

<script id="selected_template" type="text/template">
	<tr class="seleted-item selected-${persona_id}">
	<td>${nombre_completo}</td>
	<td>
		<input type="hidden" value="${persona_id}">
		<a class="btn btn-mini btn-primary" href="<?php echo Router::url('/')?>personas/${persona_id}/ver" data-remote="1" target="_blank" > Ver <i class="icon-user icon-white"></i></a>
		<a data-id="${persona_id}" class="btn btn-mini eliminar-buscar btn-danger">
			<i class="icon-remove icon-white"></i>
		</a>
	</td>
	</tr>
</script>
