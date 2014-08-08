<?php
App::uses('AppController', 'Controller');
/**
 * MotivoFins Controller
 *
 * @property MotivoFin $MotivoFin
 */
class MotivoFinsController extends AppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->MotivoFin->recursive = 0;
		$this->set('motivoFins', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->MotivoFin->exists($id)) {
			throw new NotFoundException(__('Invalid motivo fin'));
		}
		$options = array('conditions' => array('MotivoFin.' . $this->MotivoFin->primaryKey => $id));
		$this->set('motivoFin', $this->MotivoFin->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->MotivoFin->create();
			if ($this->MotivoFin->save($this->request->data)) {
				$this->Session->setFlash(__('The motivo fin has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The motivo fin could not be saved. Please, try again.'));
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
		if (!$this->MotivoFin->exists($id)) {
			throw new NotFoundException(__('Invalid motivo fin'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->MotivoFin->save($this->request->data)) {
				$this->Session->setFlash(__('The motivo fin has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The motivo fin could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('MotivoFin.' . $this->MotivoFin->primaryKey => $id));
			$this->request->data = $this->MotivoFin->find('first', $options);
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
		$this->MotivoFin->id = $id;
		if (!$this->MotivoFin->exists()) {
			throw new NotFoundException(__('Invalid motivo fin'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MotivoFin->delete()) {
			$this->Session->setFlash(__('Motivo fin deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Motivo fin was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
