<div class="patrimonios form">
<?php echo $this->Form->create('Patrimonio'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Patrimonio'); ?></legend>
	<?php
		echo $this->Form->input('persona_id');
		echo $this->Form->input('importe');
		echo $this->Form->input('fecha_declaracion');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Patrimonios'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Personas'), array('controller' => 'personas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Persona'), array('controller' => 'personas', 'action' => 'add')); ?> </li>
	</ul>
</div>
