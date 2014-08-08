<!-- esto esta en resultados.ctp -->

<?php if( empty($personas) ) : ?>
	<h4>No hay resultados para mostrar.</h4>
	<h4>Ingrese uno o más filtros o modifíquelos.</h4>
	<h5>Y si faltan datos, o tienen errores, avísenos <a href="https://docs.google.com/a/soviet.com.ar/forms/d/1NoOYENvhHXqpLO3WpB8l6R8ofJkJiShLlx2A_DfrNd0/viewform" target="_blank">aqui</a></h5>
<?php else : ?>
	<table class="table" cellpadding="0" cellspacing="0">
		<tr>
				<th><?php echo $this->Paginator->sort('apellido'); ?></th>
				<th><?php echo $this->Paginator->sort('nombre'); ?></th>
				<th></th>
		</tr>
		<?php echo $this->element('Personas/buscar/resultados_list'); ?>
	</table>
	<?php if( $this->Paginator->numbers() ) : ?>
		<div id="resultados-paging" class="paging hide">
		<?php
			echo $this->Paginator->next(' >> ',
				array('class' => 'next-page', 'tag' => false, 'data-remote' => true),
				null, 
				array()
			);
		?>
		</div>
	<?php endif; ?>
<?php endif; ?>