<?php
App::uses('AppController', 'Controller');


class ApiController extends AppController {

	var $uses = array('Cargo','Persona','Partido');

	var $quizType = array('persona','cargo','periodo');

	var $quizLevels = array(
		1=> array(1,2,3), //presidente, vicepresidente, gobernador 
		2=> array(1,2,3,4,5,6,7) //presidente, vicepresidente, gobernador, ministro, diputado, senador, intendente
		);

	public function beforeFilter() {
		$this->Auth->allow('get_cargos','get_quiz','addPersona','addPartido','getFullFechaInicio','getFullFechaFin');
		parent::beforeFilter();
	}

	public function get_quiz($nivel) {
		$data = array();

		$fields = array(
			'Persona.persona_id', 
			'Persona.nombre', 
			'Persona.apellido', 
			'CargoNominal.cargo_nominal_id',
			'CargoNominal.nombre',
			'CargoNominal.duracion',
			'Territorio.territorio_id',
			'Territorio.nombre',
			'YEAR(Cargo.fecha_inicio) as fecha_inicio',
			'YEAR(Cargo.fecha_fin) as fecha_fin',
			'Partido.partido_id',
			'Partido.nombre'
		);

		$conditions = array();

		if(in_array($nivel, array(1,2))){
			$conditions['CargoNominal.cargo_nominal_id'] = $this->quizLevels[$nivel];
		}

		$data['nivel'] = $nivel;

		//Flip coin
		$data['tipo'] = $this->quizType[rand(0,count($this->quizType)-1)];

		$data['opciones'] = $this->Cargo->find('all', array('conditions' => $conditions, 'limit'=>3, 'order' => 'rand()', 'fields'=>$fields) );

		$data['correcto'] = $data['opciones'][rand(0,count($data['opciones'])-1)];

		$this->dispatchJson($data,'get_quiz');
	}

	public function get_cargos() {

		$data = array();

		$fields = array(
			'Persona.persona_id', 
			'Persona.nombre', 
			'Persona.apellido', 
			'CargoNominal.*',
			'Territorio.nombre',
			'Cargo.fecha_inicio',
			'Cargo.fecha_fin',
			'Partido.nombre'
		);

		$conditions = array(
		);

		$order = array(
			'Persona.persona_id' => 'asc',
			'Cargo.fecha_inicio' => 'asc'
		);

		$limit = 10000;

		$page = 1;

		foreach ($this->request->query as $key => $value) {
			switch ($key) {
				case 'pag':
					$page = (int)$value;
					break;
				case 'hito':
					$time = strtotime($value);
					$date = date('Y-m-d',$time);
					$conditions["Cargo.fecha_inicio < "] = $date;
					$conditions["Cargo.fecha_fin > "] = $date;
					$order = array('CargoNominal.importancia');
					break;
				case 'cargo':
					$conditions["CargoNominal.cargo_nominal_id"] = (int)$value;
					break;
				case 'territorio':
					$conditions["Territorio.territorio_id"] = (int)$value;
					break;
				case 'partido':
					$conditions["Partido.partido_id"] = (int)$value;
					break;	
			}
		}

		$this->paginate = array(
			'fields' => $fields,
			'conditions' => $conditions,
			'order' => $order,
		    'limit' => $limit,
   		    'page' => $page
		);

		try {
		    $data = $this->paginate('Cargo');
		    //var_dump($data);
		} catch (NotFoundException $e) {
		    $data = array();
		}

  		$this->dispatchJson($data,'get_cargos');
	}

	public function addPersona($n,$a) {

		$conditions = array(
			'Persona.nombre' => $n, 
			'Persona.apellido' => $a
		);

		$res = $this->Persona->find('first', array('conditions'=> $conditions) );

		if($res){
			$data['id'] = $res['Persona']['persona_id'];
		}else{
			$toSave = array(
				'nombre' => $n, 
				'apellido' => $a
			);
			$this->Persona->save($toSave);
			$data['id'] = $this->Persona->getInsertID();
		}

		$this->dispatchJson($data,'get_quiz');
	}

	public function addPartido($n) {

		$words = explode(" ", $n);
		$acronym = "";

		foreach ($words as $w) {
		  $acronym .= $w[0];
		}

		$conditions = array(
			'Partido.nombre' => $n
		);

		$res = $this->Partido->find('first', array('conditions'=> $conditions) );

		if($res){
			$data['id'] = $res['Partido']['partido_id'];
		}else{
			$toSave = array(
				'nombre' => $n, 
				'sigla' => $acronym
			);
			$this->Partido->save($toSave);
			$data['id'] = $this->Partido->getInsertID();
		}

		$this->dispatchJson($data,'get_quiz');
	}


	public function getFullFechaInicio($y) {

		$fields = array(
			'Cargo.fecha_inicio',
			'count(1) as c'
		);

		$conditions = array(
			'YEAR(Cargo.fecha_inicio)' => $y
		);

		$group = array(
			'Cargo.fecha_inicio'
		);

		$order = array(
			'c' => 'DESC'
		);

		$res = $this->Cargo->find('first', array('conditions'=> $conditions,'group'=> $group,'order'=> $order,'fields'=> $fields) );

		if(count($res)>0){
			$data['fecha'] = $res['Cargo']['fecha_inicio']; 
		}else{
			$data['fecha'] = 'NO'.$y;		
		}

		$this->dispatchJson($data,'get_quiz');
	}

	public function getFullFechaFin($y) {

		$fields = array(
			'Cargo.fecha_fin',
			'count(1) as c'
		);

		$conditions = array(
			'YEAR(Cargo.fecha_fin)' => $y
		);

		$group = array(
			'Cargo.fecha_fin'
		);

		$order = array(
			'c' => 'DESC'
		);

		$res = $this->Cargo->find('first', array('conditions'=> $conditions,'group'=> $group,'order'=> $order,'fields'=> $fields) );

		if(count($res)>0){
			$data['fecha'] = $res['Cargo']['fecha_fin']; 
		}else{
			$data['fecha'] = 'NO'.$y;		
		}

		$this->dispatchJson($data,'get_quiz');
	}

}