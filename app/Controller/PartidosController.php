<?php
App::uses('AppController', 'Controller');
/**
 * Partidos Controller
 *
 * @property Partido $Partido
 */
class PartidosController extends AppController {

    public $components = array('RequestHandler');

	public function beforeFilter() {
		$this->Auth->allow('buscar','ver', 'comparar');
		parent::beforeFilter();
	}

	public function buscar() {

		$conditions = array();
		
		if(isset($this->request['named']['buscar'])){
			$conditions = array('Partido.nombre LIKE' => '%'.$this->request['named']['buscar'].'%');
		}

		$this->paginate = array(
	        'Partido' => array(
	            'page' => (isset($this->request['named']['page']))?$this->request['named']['page']:1,
	            'conditions' => $conditions,
	            'order' => array('Partido.nombre'=>'asc'),
	            'limit' => 10
	        )
	    );

		$this->Partido->recursive = 0;
		$this->set('partidos', $this->paginate());
		$this->layout = 'publico';
	}

	public function ver() {
		$options = array('conditions' => array('Partido.' . $this->Partido->primaryKey => $this->selectedIds));
		$this->set('partidos', $this->Partido->find('all', $options));
	}

	public function comparar(){
		$options = array('conditions' => array('Partido.' . $this->Partido->primaryKey => $this->selectedIds));
		$this->set('partidos', $this->Partido->find('all', $options));
		$this->render('ver');
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Partido->recursive = 0;
		$this->set('partidos', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Partido->exists($id)) {
			throw new NotFoundException(__('Invalid partido'));
		}
		$options = array('conditions' => array('Partido.' . $this->Partido->primaryKey => $id));
		$this->set('partido', $this->Partido->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Partido->create();
			if ($this->Partido->save($this->request->data)) {
				$this->Session->setFlash(__('The partido has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The partido could not be saved. Please, try again.'));
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
		if (!$this->Partido->exists($id)) {
			throw new NotFoundException(__('Invalid partido'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Partido->save($this->request->data)) {
				$this->Session->setFlash(__('The partido has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The partido could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Partido.' . $this->Partido->primaryKey => $id));
			$this->request->data = $this->Partido->find('first', $options);
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
		$this->Partido->id = $id;
		if (!$this->Partido->exists()) {
			throw new NotFoundException(__('Invalid partido'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Partido->delete()) {
			$this->Session->setFlash(__('Partido deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Partido was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
