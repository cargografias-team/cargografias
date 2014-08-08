<?php
App::uses('AppController', 'Controller');

class StatsController extends AppController {

	var $uses = array('Cargo','Partido','Hito','CargoNominal','Persona','Territorio');

	public function beforeFilter() {
		$this->Auth->allow('home');
		parent::beforeFilter();
	}

	public function home() {
		$this->set('count_cargos', $this->Cargo->find('count'));
		$this->set('count_partidos', $this->Partido->find('count'));
		$this->set('count_hitos', $this->Hito->find('count'));
		$this->set('count_cargos_nominales', $this->CargoNominal->find('count'));
		$this->set('count_personas', $this->Persona->find('count'));
		$this->set('count_territorios', $this->Territorio->find('count'));
	}

}
