<div class="territorios index">
	<h2><?php echo __('Territorios'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('territorio_id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('nivel'); ?></th>
			<th><?php echo $this->Paginator->sort('territorio_id_padre'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($territorios as $territorio): ?>
	<tr>
		<td><?php echo h($territorio['Territorio']['territorio_id']); ?>&nbsp;</td>
		<td><?php echo h($territorio['Territorio']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($territorio['Territorio']['nivel']); ?>&nbsp;</td>
		<td><?php echo h($territorio['Territorio']['territorio_id_padre']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $territorio['Territorio']['territorio_id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $territorio['Territorio']['territorio_id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $territorio['Territorio']['territorio_id']), null, __('Are you sure you want to delete # %s?', $territorio['Territorio']['territorio_id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Territorio'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Territorios'), array('controller' => 'territorios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Territorio'), array('controller' => 'territorios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('controller' => 'cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo'), array('controller' => 'cargos', 'action' => 'add')); ?> </li>
	</ul>
</div>
