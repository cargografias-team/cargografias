<?php

/* Modela una lista de cargos, para poder hacer queries sobre la lista (como la duracion total, x ej)
 * */
class CargosList {
	
	public function __construct(){
		$this->cargos = array();
	}
	
	public function add($cargo){
		return $this->cargos[] = $cargo;
	}
	
	public function cargo_nominal_id(){
		return $this->cargos[0]['cargo_nominal_id'];
	}
	
	public function total_anios(){
		return $this->total_dias() / 365;
	}
	
	public function total_dias(){
		$days = 0;
		foreach($this->cargos as $cargo){
			$fecha_inicio = $cargo['fecha_inicio'];
			$fecha_fin = $cargo['fecha_fin'];
			
			$inicio = new DateTime($fecha_inicio);
			$fin = new DateTime($fecha_fin);
		
			$diff = $fin->diff($inicio);
			$days += (int) $diff->format('%a');
		}
		return $days;
	}
}

?>