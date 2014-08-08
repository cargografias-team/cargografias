<?php

App::uses('Persona', 'Model');
/**
 * Object to perform the report of a person
 *
 */
class PartidoReport{
	
	public function run_for($id){
		$this->columns = array();
			
		$Partido = new Persona();
		$Partido->contain(array(
						'Cargo' => array(
									'Territorio',
									'CargoNominal' => array('TipoCargo', 'ClaseCargo')
						)
					));
		$this->data =  $Partido->find('first', array(
        					'conditions' => array('partido_id' => $id)
    						));
	
		$this->calculate_all_columns();
	}
	
	public function calculate_all_columns(){
		
	}

	
}

?>