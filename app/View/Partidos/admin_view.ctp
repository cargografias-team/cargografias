<div class="partidos view">
<h2><?php  echo __('Partido'); ?></h2>
	<dl>
		<dt><?php echo __('Partido Id'); ?></dt>
		<dd>
			<?php echo h($partido['Partido']['partido_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($partido['Partido']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sigla'); ?></dt>
		<dd>
			<?php echo h($partido['Partido']['sigla']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Partido'), array('action' => 'edit', $partido['Partido']['partido_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Partido'), array('action' => 'delete', $partido['Partido']['partido_id']), null, __('Are you sure you want to delete # %s?', $partido['Partido']['partido_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Partidos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Partido'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('controller' => 'cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo'), array('controller' => 'cargos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Cargos'); ?></h3>
	<?php if (!empty($partido['Cargo'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Cargo Id'); ?></th>
		<th><?php echo __('Fuente Id'); ?></th>
		<th><?php echo __('Fecha Inicio'); ?></th>
		<th><?php echo __('Fecha Fin'); ?></th>
		<th><?php echo __('Descripcion'); ?></th>
		<th><?php echo __('Partido Id'); ?></th>
		<th><?php echo __('Territorio Id'); ?></th>
		<th><?php echo __('Cargo Nominal Id'); ?></th>
		<th><?php echo __('Motivo Fin Id'); ?></th>
		<th><?php echo __('Persona Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($partido['Cargo'] as $cargo): ?>
		<tr>
			<td><?php echo $cargo['cargo_id']; ?></td>
			<td><?php echo $cargo['fuente_id']; ?></td>
			<td><?php echo $cargo['fecha_inicio']; ?></td>
			<td><?php echo $cargo['fecha_fin']; ?></td>
			<td><?php echo $cargo['descripcion']; ?></td>
			<td><?php echo $cargo['partido_id']; ?></td>
			<td><?php echo $cargo['territorio_id']; ?></td>
			<td><?php echo $cargo['cargo_nominal_id']; ?></td>
			<td><?php echo $cargo['motivo_fin_id']; ?></td>
			<td><?php echo $cargo['persona_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'cargos', 'action' => 'view', $cargo['cargo_id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'cargos', 'action' => 'edit', $cargo['cargo_id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'cargos', 'action' => 'delete', $cargo['cargo_id']), null, __('Are you sure you want to delete # %s?', $cargo['cargo_id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Cargo'), array('controller' => 'cargos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
