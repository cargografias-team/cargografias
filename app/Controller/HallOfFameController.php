<?php
App::uses('AppController', 'Controller');
App::uses('Persona', 'Model');
App::uses('HallOfFameStatsCreator', 'Model/HallOfFame');
class HallOfFameController extends AppController {

	public $components = array('Paginator');
	
	public $uses = array('Persona');

	public function beforeFilter() {
		$this->Auth->allow('index');
		parent::beforeFilter();
	}

	public function index() {
		$this->Paginator->setting = array(
		        	'Persona' => array(
			            'limit' => 5,
			            'contain' => array( 'HallOfFameStat' ),
				    	"joins" => array(
	                    	array(
	                        	"table" => "hall_of_fame_stat",
	                        	"alias" => "HallOfFameStat",
	                        	"type" => "inner",
	                        	"conditions" => "HallOfFameStat.persona_id = Persona.persona_id",
	                    	),
	                    ),
				));

		$stats =  $this->Paginator->paginate('Persona');
		$this->set('stats', $stats);
	}

////////////////// Admin //////////////////////

	public function admin_index() {
		
	}

	public function admin_recreate_stats(){
		$creator = new HallOfFameStatsCreator();
		$creator->create_stats();

		$this->render('admin_index');
	}

}

?>