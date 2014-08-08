<?php
	$html = $this->Html->js($this->element('Personas/buscar/resultados'));
?>
$('#search-resultados').html(decodeURIComponent('<?php echo $html; ?>'));
