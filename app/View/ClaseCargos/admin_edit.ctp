<div class="claseCargos form">
<?php echo $this->Form->create('ClaseCargo'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Clase Cargo'); ?></legend>
	<?php
		echo $this->Form->input('clase_cargo_id');
		echo $this->Form->input('nombre');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('ClaseCargo.clase_cargo_id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('ClaseCargo.clase_cargo_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Clase Cargos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Cargo Nominals'), array('controller' => 'cargo_nominals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo Nominal'), array('controller' => 'cargo_nominals', 'action' => 'add')); ?> </li>
	</ul>
</div>
