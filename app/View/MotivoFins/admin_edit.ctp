<div class="motivoFins form">
<?php echo $this->Form->create('MotivoFin'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Motivo Fin'); ?></legend>
	<?php
		echo $this->Form->input('motivo_fin_id');
		echo $this->Form->input('motivo');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('MotivoFin.motivo_fin_id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('MotivoFin.motivo_fin_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Motivo Fins'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('controller' => 'cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo'), array('controller' => 'cargos', 'action' => 'add')); ?> </li>
	</ul>
</div>
