<?php
App::uses('AppController', 'Controller');
/**
 * TipoCargos Controller
 *
 * @property TipoCargo $TipoCargo
 */
class TipoCargosController extends AppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->TipoCargo->recursive = 0;
		$this->set('tipoCargos', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->TipoCargo->exists($id)) {
			throw new NotFoundException(__('Invalid tipo cargo'));
		}
		$options = array('conditions' => array('TipoCargo.' . $this->TipoCargo->primaryKey => $id));
		$this->set('tipoCargo', $this->TipoCargo->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->TipoCargo->create();
			if ($this->TipoCargo->save($this->request->data)) {
				$this->Session->setFlash(__('The tipo cargo has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tipo cargo could not be saved. Please, try again.'));
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
		if (!$this->TipoCargo->exists($id)) {
			throw new NotFoundException(__('Invalid tipo cargo'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->TipoCargo->save($this->request->data)) {
				$this->Session->setFlash(__('The tipo cargo has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tipo cargo could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('TipoCargo.' . $this->TipoCargo->primaryKey => $id));
			$this->request->data = $this->TipoCargo->find('first', $options);
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
		$this->TipoCargo->id = $id;
		if (!$this->TipoCargo->exists()) {
			throw new NotFoundException(__('Invalid tipo cargo'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->TipoCargo->delete()) {
			$this->Session->setFlash(__('Tipo cargo deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Tipo cargo was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
