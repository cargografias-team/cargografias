<?php

App::uses('Persona', 'Model');
App::uses('HallOfFameStat', 'Model');
App::uses('CargosList', 'Model/HallOfFame');
App::uses('AniosEnMismoCargo', 'Model/HallOfFame');
App::uses('CargosDistintos', 'Model/HallOfFame');
App::uses('ReeleccionesCounter', 'Model/Persona');
class HallOfFameStatsCreator {
	
	
	public function create_stats(){
		$personas = $this->perform_query();
		
		$results = array();
		foreach($personas as $persona) {
			$this->result = array();
			$this->collect_stats_for($persona);

			$results[$persona['Persona']['persona_id']] = $this->result;
		}
		
		$this->rewrite_stats($results);
	}

	protected function rewrite_stats($results){
		$this->clean_stats();
	
		foreach($results as $id => $data){
			$hall_of_fame = new HallOfFameStat();
			$hall_of_fame->set('persona_id', $id);
			$hall_of_fame->set('anios', $data['anios']);
			$hall_of_fame->set('anios_en_mismo_cargo', $data['anios_en_mismo_cargo']);
			$hall_of_fame->set('num_cargos_distintos', $data['num_cargos_distintos']);
			$hall_of_fame->set('num_reelecciones', $data['num_reelecciones']);
			$hall_of_fame->save();
		}
	}
	
	protected function clean_stats(){
		$Hall_of_fame = new HallOfFameStat();
		$Hall_of_fame->query("truncate hall_of_fame_stat");
	}
	
	protected function perform_query(){
		$Persona = new Persona();
		$Persona->contain(array('Cargo' => array(
										'Fuente', 
										'Partido',
										'Territorio',
										'CargoNominal' => array('TipoCargo', 'ClaseCargo')
								)
					));
		$datas =  $Persona->find('all');
		return $datas;
	}

	protected function collect_stats_for($persona){
		$this->calculate_anios_en_funciones($persona);
		$this->calculate_anios_en_mismo_cargo($persona);
		$this->calculate_num_cargos_distintos($persona);
		$this->calculate_num_reelecciones($persona);
	}
	
	protected function calculate_anios_en_funciones($persona){
		$cargos = new CargosList();
		foreach($persona['Cargo'] as $cargo){
			$cargos->add($cargo);
		}
		$this->result['total_dias'] = $cargos->total_dias();
		$this->result['anios'] = $cargos->total_anios();
	}

	protected function calculate_anios_en_mismo_cargo($persona){
		$anios = AniosEnMismoCargo::calculate($persona['Cargo']);
		$this->result['anios_en_mismo_cargo'] = $anios;
	}

	protected function calculate_num_cargos_distintos($persona){
		$num = CargosDistintos::calculate($persona['Cargo']);
		$this->result['num_cargos_distintos'] = $num;
	}

	protected function calculate_num_reelecciones($persona){
		$num = ReeleccionesCounter::count_for($persona);
		$this->result['num_reelecciones'] = $num;
	}

}

?>