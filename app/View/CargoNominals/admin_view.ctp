<div class="cargoNominals view">
<h2><?php  echo __('Cargo Nominal'); ?></h2>
	<dl>
		<dt><?php echo __('Cargo Nominal Id'); ?></dt>
		<dd>
			<?php echo h($cargoNominal['CargoNominal']['cargo_nominal_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo Cargo'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargoNominal['TipoCargo']['nombre'], array('controller' => 'tipo_cargos', 'action' => 'view', $cargoNominal['TipoCargo']['tipo_cargo_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Clase Cargo'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargoNominal['ClaseCargo']['nombre'], array('controller' => 'clase_cargos', 'action' => 'view', $cargoNominal['ClaseCargo']['clase_cargo_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($cargoNominal['CargoNominal']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Duracion'); ?></dt>
		<dd>
			<?php echo h($cargoNominal['CargoNominal']['duracion']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Importancia'); ?></dt>
		<dd>
			<?php echo h($cargoNominal['CargoNominal']['importancia']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cargo Nominal'), array('action' => 'edit', $cargoNominal['CargoNominal']['cargo_nominal_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Cargo Nominal'), array('action' => 'delete', $cargoNominal['CargoNominal']['cargo_nominal_id']), null, __('Are you sure you want to delete # %s?', $cargoNominal['CargoNominal']['cargo_nominal_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargo Nominals'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo Nominal'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tipo Cargos'), array('controller' => 'tipo_cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipo Cargo'), array('controller' => 'tipo_cargos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clase Cargos'), array('controller' => 'clase_cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clase Cargo'), array('controller' => 'clase_cargos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('controller' => 'cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo'), array('controller' => 'cargos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Cargos'); ?></h3>
	<?php if (!empty($cargoNominal['Cargo'])): ?>
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
		foreach ($cargoNominal['Cargo'] as $cargo): ?>
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
