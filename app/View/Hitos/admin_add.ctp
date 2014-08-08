<div class="hitos form">
<?php echo $this->Form->create('Hito'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Hito'); ?></legend>
	<?php
		echo $this->Form->input('titulo');
		echo $this->Form->input('detalle');
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

		<li><?php echo $this->Html->link(__('List Hitos'), array('action' => 'index')); ?></li>
	</ul>
</div>
