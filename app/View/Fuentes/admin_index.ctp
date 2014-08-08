<div class="fuentes index">
	<h2><?php echo __('Fuentes'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('fuente_id'); ?></th>
			<th><?php echo $this->Paginator->sort('nombre'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($fuentes as $fuente): ?>
	<tr>
		<td><?php echo h($fuente['Fuente']['fuente_id']); ?>&nbsp;</td>
		<td><?php echo h($fuente['Fuente']['nombre']); ?>&nbsp;</td>
		<td><?php echo h($fuente['Fuente']['fecha']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $fuente['Fuente']['fuente_id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $fuente['Fuente']['fuente_id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $fuente['Fuente']['fuente_id']), null, __('Are you sure you want to delete # %s?', $fuente['Fuente']['fuente_id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Fuente'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('controller' => 'cargos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo'), array('controller' => 'cargos', 'action' => 'add')); ?> </li>
	</ul>
</div>
