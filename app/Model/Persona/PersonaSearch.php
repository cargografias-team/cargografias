<?php

App::uses('Cargo', 'Model');
class PersonaSearch {
		
	static public function is_empty_search($params){
		foreach($params as $param){
			if( !empty($param) ){
				return false;
			}
		}
		return true;
	}
		
	static public function find_persons_for_autocomplete($term){
		$instance = new self();
		return $instance->find_for_autocomplete($term);
	}

	static public function find_cargos_for_autocomplete($term){
		$instance = new self();
		return $instance->cargos_for_autocomplete($term);
	}

	static public function conditions_for_search($params){
		$instance = new self();
		return $instance->conditions_for($params);
	}

	static public function joins_for_search($params){
		$instance = new self();
		return $instance->joins_for($params);
	}
	
	public function conditions_for($params){
		if( self:: is_empty_search($params) ){
			return array( array("false") );
		}
		
		$conditions = "1=1";
		
		if( !empty($params['search']) ){
			$prepared_term = $this->prepare_term_for_like( $params['search'] );
			$conditions .= " AND CONCAT(Persona.nombre, ' ', Persona.apellido) LIKE '{$prepared_term}' ";
		}

		if( !empty($params['cargo']) ){
			$prepared_term = $this->prepare_term_for_like( $params['cargo'] );
			$conditions .= " AND CONCAT(CargoNominal.nombre, ' ', IFNULL(CargoExtendido.cargo_especifico, '')) LIKE '{$prepared_term}' ";
		}

		return array($conditions);
	}

	public function joins_for($params){

		$joins = array( 
					array(
				        'table' => 'cargo',
				        'alias' => 'Cargo',
				        'type' => 'INNER',	
				        'conditions' => array('Cargo.persona_id = Persona.persona_id')
					)
				);

		if( !empty($params['territorio']) ){
			$term = $params['territorio'];
			$condition = "Territorio.nombre LIKE '%{$term}%'";
			$joins[] = array(
		        'table' => 'territorio',
		        'alias' => 'Territorio',
		        'type' => 'INNER',	
		        'conditions' => array('Cargo.territorio_id = Territorio.territorio_id AND ' . $condition)
			);
		}


		if( !empty($params['partido']) ){
			$term = $params['partido'];
			$condition = "Partido.nombre LIKE '%{$term}%'";
			$joins[] = array(
		        'table' => 'partido',
		        'alias' => 'Partido',
		        'type' => 'INNER',	
		        'conditions' => array('Cargo.partido_id = Partido.partido_id AND ' . $condition)
			);
		}

		if( !empty($params['cargo']) ){

			$cargo = $params['cargo'];
			$prepared_term = '%' . $this->prepare_term_for_like($cargo);

			$joins[] = array(
			        'table' => 'cargo_nominal',
			        'alias' => 'CargoNominal',
			        'type' => 'LEFT OUTER',	
			        'conditions' => array('Cargo.cargo_nominal_id = CargoNominal.cargo_nominal_id')
				);
			$joins[] = array(
			        'table' => 'cargo_extendido',
			        'alias' => 'CargoExtendido',
			        'type' => 'LEFT OUTER',	
			        'conditions' => array('Cargo.cargo_extendido_id = CargoExtendido.cargo_extendido_id')
				);
		}

		return $joins;
	}
	
	public function find_for_autocomplete($term){
		$Persona = new Persona();
		$prepared_term = $this->prepare_term_for_like($term);
		$conditions = array( "CONCAT(apellido, ' ', nombre) LIKE '{$prepared_term}' " );
		return $Persona->find('all', array(
			'conditions' => $conditions,
			'fields' => array('apellido', 'nombre'),
			'contain' => false,
			'limit' => 10,
			'order' => array('apellido', 'nombre'),
		));		
	}

	public function cargos_for_autocomplete($term){
		$Cargo = new Cargo();
		$prepared_term = $this->prepare_term_for_like($term);
		return $Cargo->find('all', array(
			'joins' => array(
				array(
			        'table' => 'cargo_nominal',
			        'alias' => 'CargoNominal',
			        'type' => 'LEFT OUTER',	
			        'conditions' => array('Cargo.cargo_nominal_id = CargoNominal.cargo_nominal_id')
				),
				array(
			        'table' => 'cargo_extendido',
			        'alias' => 'CargoExtendido',
			        'type' => 'LEFT OUTER',	
			        'conditions' => array('Cargo.cargo_extendido_id = CargoExtendido.cargo_extendido_id')
				)
			),
			'conditions' => array( "CONCAT(CargoNominal.nombre, ' ', IFNULL(CargoExtendido.cargo_especifico, '')) LIKE '{$prepared_term}' " ),
			'fields' => array("DISTINCT CONCAT(CargoNominal.nombre, ' ', IFNULL(CargoExtendido.cargo_especifico, '')) as cargo"),
			'contain' => false,
			'limit' => 20,
			'order' => array('CargoNominal.nombre', 'CargoExtendido.cargo_especifico'),
		));		
	}
	
	protected function prepare_term_for_like($term){
		$words = explode(' ', $term);
		return  '%' . implode('%', $words) . '%';
	}
}

?>