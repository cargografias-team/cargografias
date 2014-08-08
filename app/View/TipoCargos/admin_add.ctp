<div class="tipoCargos form">
<?php echo $this->Form->create('TipoCargo'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Tipo Cargo'); ?></legend>
	<?php
		echo $this->Form->input('nombre');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Tipo Cargos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Cargo Nominals'), array('controller' => 'cargo_nominals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo Nominal'), array('controller' => 'cargo_nominals', 'action' => 'add')); ?> </li>
	</ul>
</div>
