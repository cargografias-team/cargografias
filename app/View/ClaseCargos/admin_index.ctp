<div class="claseCargos index">
	<h2><?php echo __('Clase Cargos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('clase_cargo_id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($claseCargos as $claseCargo): ?>
	<tr>
		<td><?php echo h($claseCargo['ClaseCargo']['clase_cargo_id']); ?>&nbsp;</td>
		<td><?php echo h($claseCargo['ClaseCargo']['nombre']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $claseCargo['ClaseCargo']['clase_cargo_id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $claseCargo['ClaseCargo']['clase_cargo_id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $claseCargo['ClaseCargo']['clase_cargo_id']), null, __('Are you sure you want to delete # %s?', $claseCargo['ClaseCargo']['clase_cargo_id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Clase Cargo'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Cargo Nominals'), array('controller' => 'cargo_nominals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo Nominal'), array('controller' => 'cargo_nominals', 'action' => 'add')); ?> </li>
	</ul>
</div>
