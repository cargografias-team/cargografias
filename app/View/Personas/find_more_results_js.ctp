<?php
	$paging = $this->Paginator->next(' >> ',
				array('class' => 'next-page', 'tag' => false, 'data-remote' => true),
				null, 
				array()
			);
	$paging = $this->Html->js($paging);
	$html = $this->Html->js($this->element('Personas/buscar/resultados_list'));
?>
$('#resultados-paging').html(decodeURIComponent('<?php echo $paging; ?>'));

$('#search-resultados table tbody').append(decodeURIComponent('<?php echo $html; ?>'));
