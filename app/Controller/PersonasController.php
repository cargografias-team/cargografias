<?php
App::uses('AppController', 'Controller');
App::uses('PersonaReport', 'Model/Persona');
App::uses('PersonaSearch', 'Model/Persona');
App::uses('ModelListCache', 'Model/Cache');
App::uses('Persona', 'Model');
/**
 * Personas Controller
 *
 * @property Persona $Persona
 */
class PersonasController extends AppController {

	public function beforeFilter() {
		$this->Auth->allow('buscar','ver', 'comparar', 'autocomplete_cargos', 'find_results');
		parent::beforeFilter();
	}

	public $components = array('RequestHandler');

	public $helpers = array('GChart.GChart', 'Cache');

	public $cacheAction = array(
    	//'comparar' => 86400
    );

	public function buscar() {

        $this->request->data = array('BuscarFormModel' => $this->request->query);

		$this->set('nombres_personas', ModelListCache::list_of('Persona'));
		$this->set('nombres_partidos', ModelListCache::list_of('Partido'));
		$this->set('nombres_territorios', ModelListCache::list_of('Territorio'));
		
		$this->layout = 'publico';	
	}

	public function find_results(){
		$this->paginate = array(
	        'Persona' => array(
	        	'paramType' => 'querystring',
	        	'fields' => array('DISTINCT Persona.apellido, Persona.nombre, CONCAT(Persona.nombre, " ", Persona.apellido) as Persona__nombre_completo'),
	            'conditions' => PersonaSearch::conditions_for_search($this->request->query),
	            'joins' => PersonaSearch::joins_for_search($this->request->query),
	            'order' => array('Persona.apellido'=>'asc'),
	            'limit' => 10
	        )
	    );
		$this->Persona->recursive = 0;
		$this->set('personas', $this->paginate());

		$this->layout = '';
		if(  isset( $this->request->query['page'] ) ){
			$this->render('find_more_results_js');
		} else {
			$this->render('find_results_js');	
		}
	}

	public function autocomplete(){
		$term = $this->request->query['term'];
		$result = PersonaSearch::find_persons_for_autocomplete($term);
		$this->autoRender = false;
		$this->set('personas', $result);
		$this->render('autocomplete');
	}

	public function autocomplete_cargos(){
		$term = $this->request->query['term'];
		$result = PersonaSearch::find_cargos_for_autocomplete($term);
		$this->autoRender = false;
		$this->set('cargos', $result);
		$this->render('autocomplete_cargos');
	}

	public function comparar() {
		$reports = PersonaReport::on_all($this->selectedIds);
		$this->set('reports', $reports);

		$this->autoRender = false;
		$this->render('comparar_js');
	}

	public function ver($id = null) {
		$report = PersonaReport::on($id);
		$this->set('report', $report);

		if( $this->request->is('ajax') ){
			$this->autoRender = false;
			$this->render('ver_js');
		}
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Persona->recursive = 0;
		$this->set('personas', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Persona->exists($id)) {
			throw new NotFoundException(__('Invalid persona'));
		}
		$options = array('conditions' => array('Persona.' . $this->Persona->primaryKey => $id));
		$this->set('persona', $this->Persona->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Persona->create();
			if ($this->Persona->save($this->request->data)) {
				$this->Session->setFlash(__('The persona has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The persona could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Persona->exists($id)) {
			throw new NotFoundException(__('Invalid persona'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Persona->save($this->request->data)) {
				$this->Session->setFlash(__('The persona has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The persona could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Persona.' . $this->Persona->primaryKey => $id));
			$this->request->data = $this->Persona->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Persona->id = $id;
		if (!$this->Persona->exists()) {
			throw new NotFoundException(__('Invalid persona'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Persona->delete()) {
			$this->Session->setFlash(__('Persona deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Persona was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
