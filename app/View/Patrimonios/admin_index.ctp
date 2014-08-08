<div class="patrimonios index">
	<h2><?php echo __('Patrimonios'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('patrimonio_id'); ?></th>
			<th><?php echo $this->Paginator->sort('persona_id'); ?></th>
			<th><?php echo $this->Paginator->sort('importe'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha_declaracion'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($patrimonios as $patrimonio): ?>
	<tr>
		<td><?php echo h($patrimonio['Patrimonio']['patrimonio_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($patrimonio['Persona']['apellido'], array('controller' => 'personas', 'action' => 'view', $patrimonio['Persona']['persona_id'])); ?>
		</td>
		<td><?php echo h($patrimonio['Patrimonio']['importe']); ?>&nbsp;</td>
		<td><?php echo h($patrimonio['Patrimonio']['fecha_declaracion']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $patrimonio['Patrimonio']['patrimonio_id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $patrimonio['Patrimonio']['patrimonio_id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $patrimonio['Patrimonio']['patrimonio_id']), null, __('Are you sure you want to delete # %s?', $patrimonio['Patrimonio']['patrimonio_id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Patrimonio'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Personas'), array('controller' => 'personas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Persona'), array('controller' => 'personas', 'action' => 'add')); ?> </li>
	</ul>
</div>
