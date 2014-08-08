<div class="cargoNominals form">
<?php echo $this->Form->create('CargoNominal'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Cargo Nominal'); ?></legend>
	<?php
		echo $this->Form->input('tipo_cargo_id');
		echo $this->Form->input('clase_cargo_id');
		echo $this->Form->input('nombre');
		echo $this->Form->input('duracion');
		echo $this->Form->input('importancia');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Cargo Nominals'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Tipo Cargos'), array('controller' => 'tipo_cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tipo Cargo'), array('controller' => 'tipo_cargos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Clase Cargos'), array('controller' => 'clase_cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Clase Cargo'), array('controller' => 'clase_cargos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('controller' => 'cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo'), array('controller' => 'cargos', 'action' => 'add')); ?> </li>
	</ul>
</div>
