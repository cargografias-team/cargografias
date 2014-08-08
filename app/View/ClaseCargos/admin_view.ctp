<div class="claseCargos view">
<h2><?php  echo __('Clase Cargo'); ?></h2>
	<dl>
		<dt><?php echo __('Clase Cargo Id'); ?></dt>
		<dd>
			<?php echo h($claseCargo['ClaseCargo']['clase_cargo_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($claseCargo['ClaseCargo']['nombre']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Clase Cargo'), array('action' => 'edit', $claseCargo['ClaseCargo']['clase_cargo_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Clase Cargo'), array('action' => 'delete', $claseCargo['ClaseCargo']['clase_cargo_id']), null, __('Are you sure you want to delete # %s?', $claseCargo['ClaseCargo']['clase_cargo_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Clase Cargos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clase Cargo'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargo Nominals'), array('controller' => 'cargo_nominals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo Nominal'), array('controller' => 'cargo_nominals', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Cargo Nominals'); ?></h3>
	<?php if (!empty($claseCargo['CargoNominal'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Cargo Nominal Id'); ?></th>
		<th><?php echo __('Tipo Cargo Id'); ?></th>
		<th><?php echo __('Clase Cargo Id'); ?></th>
		<th><?php echo __('Nombre'); ?></th>
		<th><?php echo __('Duracion'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($claseCargo['CargoNominal'] as $cargoNominal): ?>
		<tr>
			<td><?php echo $cargoNominal['cargo_nominal_id']; ?></td>
			<td><?php echo $cargoNominal['tipo_cargo_id']; ?></td>
			<td><?php echo $cargoNominal['clase_cargo_id']; ?></td>
			<td><?php echo $cargoNominal['nombre']; ?></td>
			<td><?php echo $cargoNominal['duracion']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'cargo_nominals', 'action' => 'view', $cargoNominal['cargo_nominal_id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'cargo_nominals', 'action' => 'edit', $cargoNominal['cargo_nominal_id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'cargo_nominals', 'action' => 'delete', $cargoNominal['cargo_nominal_id']), null, __('Are you sure you want to delete # %s?', $cargoNominal['cargo_nominal_id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Cargo Nominal'), array('controller' => 'cargo_nominals', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
