<div class="territorios form">
<?php echo $this->Form->create('Territorio'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Territorio'); ?></legend>
	<?php
		echo $this->Form->input('territorio_id');
		echo $this->Form->input('nombre');
		echo $this->Form->input('nivel');
		echo $this->Form->input('territorio_id_padre');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Territorio.territorio_id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Territorio.territorio_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Territorios'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Territorios'), array('controller' => 'territorios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Territorio'), array('controller' => 'territorios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('controller' => 'cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo'), array('controller' => 'cargos', 'action' => 'add')); ?> </li>
	</ul>
</div>
