<?php
	$html = $this->Html->js($this->element('Personas/buscar/ver'));
?>
$('#persona-reports').append(decodeURIComponent('<?php echo $html; ?>'));
