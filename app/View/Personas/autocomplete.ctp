<?php 

$result = array();
foreach($personas as $persona){
	$result[] = $persona['Persona']['apellido'] . ' ' . $persona['Persona']['nombre'];
}

echo json_encode($result);
?>