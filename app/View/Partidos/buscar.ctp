<?php 
$this->Html->script('lib/watch', array('inline' => false));

$this->Html->script('lib/jquery.tmpl.min', array('inline' => false));

$this->Html->script('buscar', array('inline' => false));

?>
<div class="row">
	<div class="span8">
		<h3 class="alert alert-success">Buscador de partidos</h3> 
		<div class="well">
			<p>Ingrese el nombre del partido que está buscando:</p>
			<form id="form-buscar" class="" data-key="partido" data-url="<?php echo Router::url(array('action'=>'buscar')); ?>">
				<div class="input-append">
			  		<input type="text" class="span6" value="<?php echo isset($this->request['named']['buscar'])?$this->request['named']['buscar']:''; ?>">
			  		<button type="submit" class="btn btn-success">Buscar</button>
				</div>
			</form>
		</div>
		<table class="table" cellpadding="0" cellspacing="0">
		<tr>
				<th><?php echo $this->Paginator->sort('partido_id'); ?></th>
				<th><?php echo $this->Paginator->sort('nombre'); ?></th>
				<th><?php echo $this->Paginator->sort('sigla'); ?></th>
				<th class="span2"></th>
		</tr>
		<?php foreach ($partidos as $partido): ?>
		<tr>
			<td><?php echo h($partido['Partido']['partido_id']); ?>&nbsp;</td>
			<td><?php echo h($partido['Partido']['nombre']); ?>&nbsp;</td>
			<td><?php echo h($partido['Partido']['sigla']); ?>&nbsp;</td>
			<td class="actions">
				<a class="btn btn-mini btn-primary" href="<?php echo Router::url(array('action'=>'ver', 'id'=>$partido['Partido']['partido_id'])); ?>"><i class="icon-plus icon-white"></i></a>
				<input type="hidden" value='<?php echo json_encode($partido['Partido']); ?>' />
				<a class="btn btn-mini agregar-buscar btn-warning" href="javascript:;">Comparar <i class="icon-arrow-right icon-white"></i></a>
			</td>
		</tr>
	<?php endforeach; ?>
		</table>
		<p>
		<?php
		echo $this->Paginator->counter(array(
			'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
		));
		?>	</p>
		<div class="paging">
		<?php
			echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
			echo $this->Paginator->numbers(array('separator' => '.'));
			echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
		?>
		</div>
	</div>
	<div class="span4">
			<h3 class="alert alert-warning">Selecionados</h3>
			<div id="selected-buscar">
				<form id="form-comparar-ids" class="form-search" data-url="<?php echo Router::url(array('action'=>'comparar'));?>" >
					<table class="table table-striped">
			      		<tbody>

			      		</tbody>
			      	</table>
				  <button type="submit" class="btn btn-warning disabled">Comparar</button>
  				  <a id="limpiar-buscar" type="submit" class="btn btn-danger disabled">Limpiar</a>
				</form>
			</div>
	</div>
</div>

<script id="selected_template" type="text/template">
	<tr class="seleted-item selected-${partido_id}">
      <td>${nombre}</td>
      <td><input type="hidden" value="${partido_id}"><a data-id="${partido_id}" class="btn btn-mini eliminar-buscar btn-danger"><i class="icon-trash icon-white"></i></a></td>
    </tr>
</script>