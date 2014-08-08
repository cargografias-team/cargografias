<div class="hitos form">
<?php echo $this->Form->create('Hito'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Hito'); ?></legend>
	<?php
		echo $this->Form->input('hito_id');
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

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Hito.hito_id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Hito.hito_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Hitos'), array('action' => 'index')); ?></li>
	</ul>
</div>
