<?php
App::uses('AppController', 'Controller');
/**
 * Fuentes Controller
 *
 * @property Fuente $Fuente
 */
class FuentesController extends AppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Fuente->recursive = 0;
		$this->set('fuentes', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Fuente->exists($id)) {
			throw new NotFoundException(__('Invalid fuente'));
		}
		$options = array('conditions' => array('Fuente.' . $this->Fuente->primaryKey => $id));
		$this->set('fuente', $this->Fuente->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Fuente->create();
			if ($this->Fuente->save($this->request->data)) {
				$this->Session->setFlash(__('The fuente has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fuente could not be saved. Please, try again.'));
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
		if (!$this->Fuente->exists($id)) {
			throw new NotFoundException(__('Invalid fuente'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Fuente->save($this->request->data)) {
				$this->Session->setFlash(__('The fuente has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fuente could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Fuente.' . $this->Fuente->primaryKey => $id));
			$this->request->data = $this->Fuente->find('first', $options);
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
		$this->Fuente->id = $id;
		if (!$this->Fuente->exists()) {
			throw new NotFoundException(__('Invalid fuente'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Fuente->delete()) {
			$this->Session->setFlash(__('Fuente deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Fuente was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
