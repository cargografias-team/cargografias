<?php
App::uses('AppController', 'Controller');
/**
 * Fuentes Controller
 *
 * @property Fuente $Fuente
 */
class OpenDataController extends AppController {

	var $uses = array('Cargo');

	public function admin_index() {
		$db =& $this->Cargo->getDataSource(); // or any other model 
		$this->set('tablas',$db->listSources());
	}

	public function admin_create() {
		function toGenerate($var){
		    return($var === "1");
		}
		$tables = $this->request->data['Cargo'];
		$tables = array_filter($tables,"toGenerate");
		//var_dump($tables);
	}



}