<?php 

$result = array();
foreach($cargos as $cargo){
	$result[] = $cargo[0]['cargo'];
}

echo json_encode($result);
?>