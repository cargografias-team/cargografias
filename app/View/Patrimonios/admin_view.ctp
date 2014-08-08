<div class="patrimonios view">
<h2><?php  echo __('Patrimonio'); ?></h2>
	<dl>
		<dt><?php echo __('Patrimonio Id'); ?></dt>
		<dd>
			<?php echo h($patrimonio['Patrimonio']['patrimonio_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Persona'); ?></dt>
		<dd>
			<?php echo $this->Html->link($patrimonio['Persona']['apellido'], array('controller' => 'personas', 'action' => 'view', $patrimonio['Persona']['persona_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Importe'); ?></dt>
		<dd>
			<?php echo h($patrimonio['Patrimonio']['importe']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha Declaracion'); ?></dt>
		<dd>
			<?php echo h($patrimonio['Patrimonio']['fecha_declaracion']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Patrimonio'), array('action' => 'edit', $patrimonio['Patrimonio']['patrimonio_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Patrimonio'), array('action' => 'delete', $patrimonio['Patrimonio']['patrimonio_id']), null, __('Are you sure you want to delete # %s?', $patrimonio['Patrimonio']['patrimonio_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Patrimonios'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Patrimonio'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Personas'), array('controller' => 'personas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Persona'), array('controller' => 'personas', 'action' => 'add')); ?> </li>
	</ul>
</div>
