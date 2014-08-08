<?php
App::uses('AppController', 'Controller');
/**
 * ClaseCargos Controller
 *
 * @property ClaseCargo $ClaseCargo
 */
class ClaseCargosController extends AppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->ClaseCargo->recursive = 0;
		$this->set('claseCargos', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->ClaseCargo->exists($id)) {
			throw new NotFoundException(__('Invalid clase cargo'));
		}
		$options = array('conditions' => array('ClaseCargo.' . $this->ClaseCargo->primaryKey => $id));
		$this->set('claseCargo', $this->ClaseCargo->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->ClaseCargo->create();
			if ($this->ClaseCargo->save($this->request->data)) {
				$this->Session->setFlash(__('The clase cargo has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The clase cargo could not be saved. Please, try again.'));
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
		if (!$this->ClaseCargo->exists($id)) {
			throw new NotFoundException(__('Invalid clase cargo'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ClaseCargo->save($this->request->data)) {
				$this->Session->setFlash(__('The clase cargo has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The clase cargo could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ClaseCargo.' . $this->ClaseCargo->primaryKey => $id));
			$this->request->data = $this->ClaseCargo->find('first', $options);
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
		$this->ClaseCargo->id = $id;
		if (!$this->ClaseCargo->exists()) {
			throw new NotFoundException(__('Invalid clase cargo'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ClaseCargo->delete()) {
			$this->Session->setFlash(__('Clase cargo deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Clase cargo was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
