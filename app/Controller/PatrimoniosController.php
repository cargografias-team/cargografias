<?php
App::uses('AppController', 'Controller');
/**
 * Patrimonios Controller
 *
 * @property Patrimonio $Patrimonio
 */
class PatrimoniosController extends AppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Patrimonio->recursive = 0;
		$this->set('patrimonios', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Patrimonio->exists($id)) {
			throw new NotFoundException(__('Invalid patrimonio'));
		}
		$options = array('conditions' => array('Patrimonio.' . $this->Patrimonio->primaryKey => $id));
		$this->set('patrimonio', $this->Patrimonio->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Patrimonio->create();
			if ($this->Patrimonio->save($this->request->data)) {
				$this->Session->setFlash(__('The patrimonio has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The patrimonio could not be saved. Please, try again.'));
			}
		}
		$personas = $this->Patrimonio->Persona->find('list');
		$this->set(compact('personas'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Patrimonio->exists($id)) {
			throw new NotFoundException(__('Invalid patrimonio'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Patrimonio->save($this->request->data)) {
				$this->Session->setFlash(__('The patrimonio has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The patrimonio could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Patrimonio.' . $this->Patrimonio->primaryKey => $id));
			$this->request->data = $this->Patrimonio->find('first', $options);
		}
		$personas = $this->Patrimonio->Persona->find('list');
		$this->set(compact('personas'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Patrimonio->id = $id;
		if (!$this->Patrimonio->exists()) {
			throw new NotFoundException(__('Invalid patrimonio'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Patrimonio->delete()) {
			$this->Session->setFlash(__('Patrimonio deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Patrimonio was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
