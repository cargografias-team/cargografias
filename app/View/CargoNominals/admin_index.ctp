<div class="cargoNominals index">
	<h2><?php echo __('Cargo Nominals'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('cargo_nominal_id'); ?></th>
			<th><?php echo $this->Paginator->sort('tipo_cargo_id'); ?></th>
			<th><?php echo $this->Paginator->sort('clase_cargo_id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('duracion'); ?></th>
			<th><?php echo $this->Paginator->sort('importancia'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($cargoNominals as $cargoNominal): ?>
	<tr>
		<td><?php echo h($cargoNominal['CargoNominal']['cargo_nominal_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($cargoNominal['TipoCargo']['nombre'], array('controller' => 'tipo_cargos', 'action' => 'view', $cargoNominal['TipoCargo']['tipo_cargo_id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($cargoNominal['ClaseCargo']['nombre'], array('controller' => 'clase_cargos', 'action' => 'view', $cargoNominal['ClaseCargo']['clase_cargo_id'])); ?>
		</td>
		<td><?php echo h($cargoNominal['CargoNominal']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($cargoNominal['CargoNominal']['duracion']); ?>&nbsp;</td>
		<td><?php echo h($cargoNominal['CargoNominal']['importancia']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $cargoNominal['CargoNominal']['cargo_nominal_id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cargoNominal['CargoNominal']['cargo_nominal_id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $cargoNominal['CargoNominal']['cargo_nominal_id']), null, __('Are you sure you want to delete # %s?', $cargoNominal['CargoNominal']['cargo_nominal_id'])); ?>
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
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Cargo Nominal'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Tipo Cargos'), array('controller' => 'tipo_cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipo Cargo'), array('controller' => 'tipo_cargos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clase Cargos'), array('controller' => 'clase_cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clase Cargo'), array('controller' => 'clase_cargos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('controller' => 'cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo'), array('controller' => 'cargos', 'action' => 'add')); ?> </li>
	</ul>
</div>
