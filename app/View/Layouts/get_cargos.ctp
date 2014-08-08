<?php 
$result = array();
$result['metadata'] = $this->Paginator->params['paging']['Cargo'];
$result['data'] = array();

$maxDate=0;
$minDate=9999;

$n;

$lastId=0;

$item;

foreach ($data as $c) {

	if($lastId!=(int)$c['Persona']['persona_id']){
		$lastId = $c['Persona']['persona_id'];
		if(isset($item))
			$result['data'][] = $item;

		$item = array();
		$item['nombre'] = $c['Persona']['apellido'] . ', ' . $c['Persona']['nombre']  ;
		$item['id'] = $c['Persona']['persona_id'];
	}

	$n = array();

	//Desde
	$n['inicio'] = $c['Cargo']['fecha_inicio'];
	/*$n['inicio'] = (array) split('-', $c['Cargo']['fecha_inicio']);
	$n['inicio'] = (int)$n['inicio'][0];  

	$minDate = ($minDate>$n['inicio'])?$n['inicio']:$minDate;*/

	//Hasta
	$n['fin'] = $c['Cargo']['fecha_fin'];
	/*$n['fin'] = (array) split('-', $c['Cargo']['fecha_fin']);
	$n['fin'] = (int)$n['fin'][0];  

	$maxDate = ($maxDate<$n['fin'])?$n['fin']:$maxDate;*/

	//Cargo
	$n['cargo'] = $c['CargoNominal']['nombre'];

	//Cargo
	$n['lugar'] = $c['Territorio']['nombre'];

	//Duracion
	$n['duracion'] = (int)$c['CargoNominal']['duracion'];

	$n['tipo_cargo'] = (int)$c['CargoNominal']['tipo_cargo_id'];

	$n['clase_cargo'] = (int)$c['CargoNominal']['clase_cargo_id'];

	//Partido
	$n['partido'] = $c['Partido']['nombre'];

	$item['cargos'][] = $n;

}

//last item
if(isset($item))
	$result['data'][] = $item;

/*$result['metadata']['maxDate'] = $maxDate;
$result['metadata']['minDate'] = $minDate;*/

unset($data);

echo json_encode($result);

?>
