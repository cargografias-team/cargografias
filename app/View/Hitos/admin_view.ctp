<div class="hitos view">
<h2><?php  echo __('Hito'); ?></h2>
	<dl>
		<dt><?php echo __('Hito Id'); ?></dt>
		<dd>
			<?php echo h($hito['Hito']['hito_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Titulo'); ?></dt>
		<dd>
			<?php echo h($hito['Hito']['titulo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Detalle'); ?></dt>
		<dd>
			<?php echo h($hito['Hito']['detalle']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha'); ?></dt>
		<dd>
			<?php echo h($hito['Hito']['fecha']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Hito'), array('action' => 'edit', $hito['Hito']['hito_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Hito'), array('action' => 'delete', $hito['Hito']['hito_id']), null, __('Are you sure you want to delete # %s?', $hito['Hito']['hito_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Hitos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Hito'), array('action' => 'add')); ?> </li>
	</ul>
</div>
