<div class="hitos index">
	<h2><?php echo __('Hitos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('hito_id'); ?></th>
			<th><?php echo $this->Paginator->sort('titulo'); ?></th>
			<th><?php echo $this->Paginator->sort('detalle'); ?></th>
			<th><?php echo $this->Paginator->sort('fecha'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($hitos as $hito): ?>
	<tr>
		<td><?php echo h($hito['Hito']['hito_id']); ?>&nbsp;</td>
		<td><?php echo h($hito['Hito']['titulo']); ?>&nbsp;</td>
		<td><?php echo h($hito['Hito']['detalle']); ?>&nbsp;</td>
		<td><?php echo h($hito['Hito']['fecha']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $hito['Hito']['hito_id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $hito['Hito']['hito_id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $hito['Hito']['hito_id']), null, __('Are you sure you want to delete # %s?', $hito['Hito']['hito_id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Hito'), array('action' => 'add')); ?></li>
	</ul>
</div>
