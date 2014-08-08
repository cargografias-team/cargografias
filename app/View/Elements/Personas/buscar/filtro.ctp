<!-- esto esta en filtro.ctp -->
<div class="span6">
	<?php echo $this->Form->create('BuscarFormModel', array(
					'url' => array('controller' => 'personas', 'action' => 'find_results'),
					'type' => 'GET', 
					'data-key'=>"persona",
					'data-remote'=>'1' 
				));
	?>
	<p style="margin-left:10px;">Seleccione los filtros de la búsqueda:</p>
	<div class="search-row clearfix span3">
		<?php
			echo $this->Form->input('cargo', array(
				'class' => 'span3', 
				'label' => false, 
				'div' => false,
				'placeholder' => '(todos los cargos)',
				'data-url-autocomple' => Router::url(array('action'=>'autocomplete_cargos')),
			));
		?>
	</div>			
	<div class="search-row clearfix span3">
		<?php
			echo $this->Form->input('partido', array(
				'class' => 'span3', 
				'label' => false, 
				'div' => false,
				'placeholder' => '(todos los partidos)',
			));
		?>
	</div>			
	<div class="search-row clearfix span3">
		<?php
			echo $this->Form->input('territorio', array(
				'class' => 'span3', 
				'label' => false, 
				'div' => false,
				'placeholder' => '(todos los territorios)',
			));
		?>
	</div>
	<div class="search-row clearfix span3">
		<?php
			echo $this->Form->input('search', array(
				'class' => 'span3', 
				'label' => false, 
				'div' => false,
				'placeholder' => 'Filtrar por Nombre y/o Apellido',
			));
		?>
	</div>
	<div class="search-row clearfix span3">
		<span style="float:left"><a id="limpiar-buscar-filtro" type="submit" class="btn btn-danger" >Limpiar Filtros</a></span>
		<?php echo $this->Form->submit('Buscar', array('id'=>"buscar-submit", 'class'=>'btn btn-success', 'div' => false)); ?>
	</div>
	<?php echo $this->Form->end(); ?>
	<script type='text/javascript'>
		<?php echo $this->Html->to_json_variable('nombresPersonas', $nombres_personas); ?>
		
		<?php echo $this->Html->to_json_variable('nombresTerritorios', $nombres_territorios); ?>
		
		<?php echo $this->Html->to_json_variable('nombresPartidos', $nombres_partidos); ?>
	</script>
</div>
