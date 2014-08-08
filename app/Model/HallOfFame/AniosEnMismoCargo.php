<?php

class AniosEnMismoCargo {

	static public function calculate($cargos){
		$instance = new self($cargos);
		return $instance->do_calculate();
	}
	
	public function __construct($cargos){
		$this->cargos = $cargos;
	}
	
	public function do_calculate(){
		$lists = array();
		foreach($this->cargos as $cargo){
			$included = false;
			foreach($lists as $list){
				if( $list->cargo_nominal_id() == $cargo['cargo_nominal_id'] ){
					$list->add($cargo);
					$included = true;
				}
			}
			if( !$included ){
				$list = new CargosList();
				$list->add($cargo);
				$lists[] = $list;
			}
		}
		return $this->find_max_in($lists);
	}

	protected function find_max_in($lists){
		$max_years = 0;
		foreach($lists as $list){
			if( $max_years < $list->total_anios() ){
				$max_years = $list->total_anios();
				$max = $list;
			}			
		}
		return $max_years;
	}
}

?>