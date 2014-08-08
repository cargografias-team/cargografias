<div class="cargos form">
<?php echo $this->Form->create('Cargo'); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Cargo'); ?></legend>
	<?php
		echo $this->Form->input('cargo_id');

		echo $this->Chosen->select(
		    'persona_id',
		    $personas,
		    array('data-placeholder' => 'Seleccione una persona', 'multiple' => false)
		);

		echo $this->Chosen->select(
		    'cargo_nominal_id',
		    $cargoNominals,
		    array('data-placeholder' => 'Seleccione un Cargo', 'multiple' => false)
		);

		echo $this->Form->input('descripcion');

		echo $this->Chosen->select(
		    'territorio_id',
		    $territorios,
		    array('data-placeholder' => 'Seleccione un territorio', 'multiple' => false)
		);

		echo $this->Chosen->select(
		    'partido_id',
		    $partidos,
		    array('data-placeholder' => 'Seleccione un partido', 'multiple' => false)
		);

		echo $this->Form->input('fecha_inicio',
			array(
			    'minYear' => 1900,
			    'maxYear' => date('Y') + 4
			)
		);
		echo $this->Form->input('fecha_fin',
			array(
			    'minYear' => 1900,
			    'maxYear' => date('Y') + 4
			)
		);

		echo $this->Chosen->select(
		    'motivo_fin_id',
		    $motivoFins,
		    array('data-placeholder' => 'Seleccione un Motivo de fin', 'multiple' => false)
		);

		echo $this->Chosen->select(
		    'fuente_id',
		    $fuentes,
		    array('data-placeholder' => 'Seleccione una fuente', 'multiple' => false)
		);

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Cargo.cargo_id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Cargo.cargo_id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Cargos'), array('action' => 'index')); ?></li>
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
