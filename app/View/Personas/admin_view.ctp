<div class="personas view">
<h2><?php  echo __('Persona'); ?></h2>
	<dl>
		<dt><?php echo __('Persona Id'); ?></dt>
		<dd>
			<?php echo h($persona['Persona']['persona_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($persona['Persona']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Apellido'); ?></dt>
		<dd>
			<?php echo h($persona['Persona']['apellido']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Persona'), array('action' => 'edit', $persona['Persona']['persona_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Persona'), array('action' => 'delete', $persona['Persona']['persona_id']), null, __('Are you sure you want to delete # %s?', $persona['Persona']['persona_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Personas'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Persona'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('controller' => 'cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo'), array('controller' => 'cargos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Patrimonios'), array('controller' => 'patrimonios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Patrimonio'), array('controller' => 'patrimonios', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Cargos'); ?></h3>
	<?php if (!empty($persona['Cargo'])): ?>
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
		foreach ($persona['Cargo'] as $cargo): ?>
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
<div class="related">
	<h3><?php echo __('Related Patrimonios'); ?></h3>
	<?php if (!empty($persona['Patrimonio'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Patrimonio Id'); ?></th>
		<th><?php echo __('Persona Id'); ?></th>
		<th><?php echo __('Importe'); ?></th>
		<th><?php echo __('Fecha Declaracion'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($persona['Patrimonio'] as $patrimonio): ?>
		<tr>
			<td><?php echo $patrimonio['patrimonio_id']; ?></td>
			<td><?php echo $patrimonio['persona_id']; ?></td>
			<td><?php echo $patrimonio['importe']; ?></td>
			<td><?php echo $patrimonio['fecha_declaracion']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'patrimonios', 'action' => 'view', $patrimonio['patrimonio_id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'patrimonios', 'action' => 'edit', $patrimonio['patrimonio_id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'patrimonios', 'action' => 'delete', $patrimonio['patrimonio_id']), null, __('Are you sure you want to delete # %s?', $patrimonio['patrimonio_id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Patrimonio'), array('controller' => 'patrimonios', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
