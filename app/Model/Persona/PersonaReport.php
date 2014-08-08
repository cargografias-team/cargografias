<?php

App::uses('Persona', 'Model');
App::uses('ReeleccionesCounter', 'Model/Persona');
/**
 * Object to perform the report of a person
 *
 */
class PersonaReport{
	
	static public function on($persona_id){
		$reports = self::on_all( array($persona_id) );
		return $reports[0];
	}

	static public function on_all($persona_ids){
		$datas = self::perform_query_on($persona_ids);
		$reports = array();
		foreach($datas as $data){
			$reports[] = new self($data);
		}
		return $reports;
	}
	
	static public function perform_query_on($persona_ids){
		$Persona = new Persona();
		$Persona->contain(array(
						'Cargo' => array(
									'Fuente', 
									'Partido',
									'Territorio',
									'CargoNominal' => array('TipoCargo', 'ClaseCargo')
						)
					));
		$datas =  $Persona->find('all', array(
        					'conditions' => array('persona_id' => $persona_ids)
    						));
		return $datas;
	}
	
	public function __construct($data){
		$this->run_for($data);
	}
	
	public function run_for($data){
		$this->columns = array();
			
		$this->data = $data;
	
		$this->calculate_all_columns();
		
		$this->cargos = $this->data['Cargo'];
	}
	
	public function calculate_all_columns(){
		$this->calculate_number_of_cargos();
		$this->calculate_fecha_primer_cargo();
		$this->calculate_fecha_ultimo_cargo();
		$this->calculate_cargos_ejecutivos();
		$this->calculate_cargos_legislativos();
		$this->calculate_cargos_judiciales();
		$this->calculate_cargos_electivos();
		$this->calculate_cargos_no_electivos();
		$this->calculate_reelecciones();
		$this->calculate_cargos_por_territorios();
		$this->calculate_cargos_por_partidos();
		$this->calculate_cargos_por_poderes();
		$this->calculate_cargos_por_electivos();
	}

	public function calculate_number_of_cargos(){
		$this->columns['Cargos'] = count($this->data['Cargo']);
	}

	public function calculate_fecha_primer_cargo(){
		$this->columns['Primero'] = $this->data['Cargo'][0]['fecha_inicio'];
	}

	public function calculate_fecha_ultimo_cargo(){
		$n = count($this->data['Cargo']);
		$this->columns['Ultimo'] = $this->data['Cargo'][$n-1]['fecha_fin'];
	}

	public function calculate_cargos_ejecutivos(){
		$k = $this->count_tipo_cargos('Ejecutivo');
		$this->columns['Ejec.'] = $k;
	}

	public function calculate_cargos_legislativos(){
		$k = $this->count_tipo_cargos('Legislativo');
		$this->columns['Legis.'] = $k;
	}

	public function calculate_cargos_judiciales(){
		$k = $this->count_tipo_cargos('Judicial');
		$this->columns['Jud.'] = $k;
	}

	public function calculate_cargos_electivos(){
		$k = $this->count_cargos_electivos('Electivo');
		$this->columns['Elect.'] = $k;
	}

	public function calculate_cargos_no_electivos(){
		$k = $this->count_cargos_electivos('No electivo');
		$this->columns['No elect.'] = $k;
	}

	public function calculate_reelecciones(){
		$this->columns['Reelecc.'] = ReeleccionesCounter::count_for($this->data);
	}

	public function calculate_territorios(){
		$terrotorios = array();
		foreach($this->data['Cargo'] as $cargo){
			array_push($terrotorios, $cargo['Territorio']['nombre']);
		}
		$this->columns['Territorios'] = implode(', ', $terrotorios);
	}

	public function calculate_partidos(){
		$partidos = array();
		foreach($this->data['Cargo'] as $cargo){
			if( isset($cargo['Partido']['nombre']) ){
				array_push($partidos, $cargo['Partido']['nombre']);				
			} else {
				array_push($partidos, 'Desconocido');
			}
		}
		$this->columns['Partidos'] = implode(', ', $partidos);
	}
	
	public function calculate_cargos_por_territorios(){
		$territorios = array();
		foreach($this->data['Cargo'] as $cargo){
			$territorio = $cargo['Territorio']['nombre'];
			if( isset($territorios[$territorio]) ){
				$territorios[$territorio][1] += 1;
			} else {
				$territorios[$territorio] = array($territorio, 1);
			}
		}

		$this->territorios_chart = array(
					  'labels' => array(
					    array('string' => 'Territorio'),
					    array('number' => '# Cargos'),
					  ),
					  'data' => $territorios,
					  'title' => 'Cargos por territorio',
					  'type' => 'pie'
					);
	}

	public function calculate_cargos_por_partidos(){
		$partidos = array();
		foreach($this->data['Cargo'] as $cargo){
			$partido = isset($cargo['Partido']['nombre']) ? $cargo['Partido']['nombre'] : 'Desconocido';
			if( isset($partidos[$partido]) ){
				$partidos[$partido][1] += 1;
			} else {
				$partidos[$partido] = array($partido, 1);
			}
		}

		$this->partidos_chart = array(
					  'labels' => array(
					    array('string' => 'Partido'),
					    array('number' => '# Cargos'),
					  ),
					  'data' => $partidos,
					  'title' => 'Cargos por partido',
					  'type' => 'pie'
					);
	}

	public function calculate_cargos_por_poderes(){
		$poderes = array();
		foreach($this->data['Cargo'] as $cargo){
			$poder = $cargo['CargoNominal']['TipoCargo']['nombre'];
			if( isset($poderes[$poder]) ){
				$poderes[$poder][1] += 1;
			} else {
				$poderes[$poder] = array($poder, 1);
			}
		}

		$this->poderes_chart = array(
					  'labels' => array(
					    array('string' => 'Poder'),
					    array('number' => '# Cargos'),
					  ),
					  'data' => $poderes,
					  'title' => 'Cargos por poder',
					  'type' => 'pie'
					);
	}

	public function calculate_cargos_por_electivos(){
		$electivos = array();
		foreach($this->data['Cargo'] as $cargo){
			$electivo = $cargo['CargoNominal']['ClaseCargo']['nombre'];
			if( isset($electivos[$electivo]) ){
				$electivos[$electivo][1] += 1;
			} else {
				$electivos[$electivo] = array($electivo, 1);
			}
		}

		$this->electivos_chart = array(
					  'labels' => array(
					    array('string' => 'Electivo'),
					    array('number' => '# Cargos'),
					  ),
					  'data' => $electivos,
					  'title' => 'Cargos por electivo/no electivo',
					  'type' => 'pie'
					);
	}
	
	// helpers
	public function count_tipo_cargos($tipo){
		$n = 0;
		foreach($this->data['Cargo'] as $cargo){
			if( $cargo['CargoNominal']['TipoCargo']['nombre'] == $tipo ){
				$n++;
			}
		}
		return $n;
	}

	public function count_cargos_electivos($tipo){
		$n = 0;
		foreach($this->data['Cargo'] as $cargo){
			if( $cargo['CargoNominal']['ClaseCargo']['nombre'] == $tipo ){
				$n++;
			}
		}
		return $n;
	}

	
}

?>