<div class="cargos index">
	<h2><?php echo __('Cargos'); ?></h2>
	<?php echo implode("<br />", $csv_data); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Cargo'), array('action' => 'add')); ?></li>
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
