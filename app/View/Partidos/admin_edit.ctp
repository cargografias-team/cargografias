<div class="partidos form">
<?php echo $this->Form->create('Partido'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Partido'); ?></legend>
	<?php
		echo $this->Form->input('partido_id');
		echo $this->Form->input('nombre');
		echo $this->Form->input('sigla');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Partido.partido_id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Partido.partido_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Partidos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('controller' => 'cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo'), array('controller' => 'cargos', 'action' => 'add')); ?> </li>
	</ul>
</div>
