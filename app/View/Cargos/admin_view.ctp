<div class="cargos view">
<h2><?php  echo __('Cargo'); ?></h2>
	<dl>
		<dt><?php echo __('Cargo Id'); ?></dt>
		<dd>
			<?php echo h($cargo['Cargo']['cargo_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Persona'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargo['Persona']['apellido'], array('controller' => 'personas', 'action' => 'view', $cargo['Persona']['persona_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha Inicio'); ?></dt>
		<dd>
			<?php echo h($cargo['Cargo']['fecha_inicio']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fecha Fin'); ?></dt>
		<dd>
			<?php echo h($cargo['Cargo']['fecha_fin']); ?>
			&nbsp;
		</dd>
		<!-- <dt><?php echo __('Descripcion'); ?></dt>
		<dd>
			<?php echo h($cargo['Cargo']['descripcion']); ?>
			&nbsp;
		</dd> -->
		<dt><?php echo __('Partido'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargo['Partido']['nombre'], array('controller' => 'partidos', 'action' => 'view', $cargo['Partido']['partido_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Territorio'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargo['Territorio']['nombre'], array('controller' => 'territorios', 'action' => 'view', $cargo['Territorio']['territorio_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cargo Nominal'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargo['CargoNominal']['nombre'], array('controller' => 'cargo_nominals', 'action' => 'view', $cargo['CargoNominal']['cargo_nominal_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Motivo Fin'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargo['MotivoFin']['motivo'], array('controller' => 'motivo_fins', 'action' => 'view', $cargo['MotivoFin']['motivo_fin_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fuente'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cargo['Fuente']['nombre'], array('controller' => 'fuentes', 'action' => 'view', $cargo['Fuente']['fuente_id'])); ?>
			&nbsp;
		</dd>
		
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cargo'), array('action' => 'edit', $cargo['Cargo']['cargo_id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Cargo'), array('action' => 'delete', $cargo['Cargo']['cargo_id']), null, __('Are you sure you want to delete # %s?', $cargo['Cargo']['cargo_id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Fuentes'), array('controller' => 'fuentes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Fuente'), array('controller' => 'fuentes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Partidos'), array('controller' => 'partidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Partido'), array('controller' => 'partidos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Territorios'), array('controller' => 'territorios', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Territorio'), array('controller' => 'territorios', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cargo Nominals'), array('controller' => 'cargo_nominals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cargo Nominal'), array('controller' => 'cargo_nominals', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Motivo Fins'), array('controller' => 'motivo_fins', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Motivo Fin'), array('controller' => 'motivo_fins', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Personas'), array('controller' => 'personas', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Persona'), array('controller' => 'personas', 'action' => 'add')); ?> </li>
	</ul>
</div>
