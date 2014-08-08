<?php

/* Clase para contar el numero de reelecciones dados una coleccion de cargos.
 * Se considera una reeleccion si tiene 2 cargos consecutivos con menos de 6 meses de diferencia,
 * con el mismo cargo y el mismo territorio.
 * */
class ReeleccionesCounter {
	
	static public function count_for($data){
		$instance = new self($data);
		return $instance->count();
	}
	
	public function __construct($data){
		$this->cargos = $data['Cargo'];
	}
	
	public function count(){
		$counter = 0;
		for($i=0; $i < count($this->cargos) - 2; $i++){
			$pre = $this->cargos[$i];
			$post = $this->cargos[$i+1];
			if( $this->is_reeleccion($pre, $post) ){
				$counter++;
			}
		}
		return $counter;
	}
	
	protected function is_reeleccion($pre, $post){
		$are_near_in_time = $this->are_near_in_time($pre, $post);
		
		return 
			$are_near_in_time
			&&
			$pre['territorio_id'] == $post['territorio_id']
			&&
			$pre['cargo_nominal_id'] == $post['cargo_nominal_id'];
	}
	
	protected function are_near_in_time($pre, $post){
		$fecha_fin = $pre['fecha_fin'];
		$fecha_inicio = $post['fecha_inicio'];
		
		if( empty($fecha_fin) || empty($fecha_inicio) )
			return false;
		
		$fecha_fin = new DateTime($fecha_fin);
		$fecha_inicio = new DateTime($fecha_inicio);

		$fecha_fin->add(new DateInterval('P180D'));
		
		return $fecha_fin > $fecha_inicio;
	}
}

?>