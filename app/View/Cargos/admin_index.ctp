<div class="cargos index">
	<h2><?php echo __('Cargos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('cargo_id'); ?></th>
			<th><?php echo $this->Paginator->sort('fuente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha_inicio'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha_fin'); ?></th>
			<!-- <th><?php echo $this->Paginator->sort('descripcion'); ?></th> -->
			<th><?php echo $this->Paginator->sort('partido_id'); ?></th>
			<th><?php echo $this->Paginator->sort('territorio_id'); ?></th>
			<th><?php echo $this->Paginator->sort('cargo_nominal_id'); ?></th>
			<th><?php echo $this->Paginator->sort('motivo_fin_id'); ?></th>
			<th><?php echo $this->Paginator->sort('persona_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($cargos as $cargo): ?>
	<tr>
		<td><?php echo h($cargo['Cargo']['cargo_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($cargo['Fuente']['nombre'], array('controller' => 'fuentes', 'action' => 'view', $cargo['Fuente']['fuente_id'])); ?>
		</td>
		<td><?php echo h($cargo['Cargo']['fecha_inicio']); ?>&nbsp;</td>
		<td><?php echo h($cargo['Cargo']['fecha_fin']); ?>&nbsp;</td>
		<td><?php echo h($cargo['Cargo']['descripcion']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($cargo['Partido']['nombre'], array('controller' => 'partidos', 'action' => 'view', $cargo['Partido']['partido_id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($cargo['Territorio']['nombre'], array('controller' => 'territorios', 'action' => 'view', $cargo['Territorio']['territorio_id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($cargo['CargoNominal']['nombre'], array('controller' => 'cargo_nominals', 'action' => 'view', $cargo['CargoNominal']['cargo_nominal_id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($cargo['MotivoFin']['motivo'], array('controller' => 'motivo_fins', 'action' => 'view', $cargo['MotivoFin']['motivo_fin_id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($cargo['Persona']['nombre'] . ' ' . $cargo['Persona']['apellido'], array('controller' => 'personas', 'action' => 'view', $cargo['Persona']['persona_id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $cargo['Cargo']['cargo_id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cargo['Cargo']['cargo_id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $cargo['Cargo']['cargo_id']), null, __('Are you sure you want to delete # %s?', $cargo['Cargo']['cargo_id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Cargo'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Fuentes'), array('controller' => 'fuentes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Fuente'), array('controller' => 'fuentes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Partidos'), array('controller' => 'partidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Partido'), array('controller' => 'partidos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Territorios'), array('controller' => 'territorios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Territorio'), array('controller' => 'territorios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargo Nominals'), array('controller' => 'cargo_nominals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo Nominal'), array('controller' => 'cargo_nominals', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Motivo Fins'), array('controller' => 'motivo_fins', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Motivo Fin'), array('controller' => 'motivo_fins', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Personas'), array('controller' => 'personas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Persona'), array('controller' => 'personas', 'action' => 'add')); ?> </li>
	</ul>
</div>
