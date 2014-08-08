<h2>Generar CSV</h2>
<?php echo $this->Form->create(null, array(
    'url' => array('controller' => 'openData', 'action' => 'create','prefix' => 'admin')
)); ?>

<?php foreach ($tablas as $key => $value) { ?> 
	<?php echo $this->Form->input($value, array(
		'label' => $value,
		'type' => 'checkbox'
	)); ?>
<?php }  ?>

<?php echo $this->Form->submit(); ?>

<?php echo $this->Form->end(); ?>