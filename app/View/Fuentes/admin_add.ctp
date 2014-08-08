<div class="fuentes form">
<?php echo $this->Form->create('Fuente'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Fuente'); ?></legend>
	<?php
		echo $this->Form->input('nombre');
		echo $this->Form->input('fecha',array(
			    'minYear' => 1950,
			    'maxYear' => date('Y') + 4
			)
		);
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Fuentes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('controller' => 'cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo'), array('controller' => 'cargos', 'action' => 'add')); ?> </li>
	</ul>
</div>
