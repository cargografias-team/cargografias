<?php
	if( count($reports) == 0 ){
		$html = '';
	} else {
		$html = $this->Html->js($this->element('Personas/buscar/comparar'));	
	}
?>
$('#comparacion-reports').html(decodeURIComponent('<?php echo $html; ?>'));
