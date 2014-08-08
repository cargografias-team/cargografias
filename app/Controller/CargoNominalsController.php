<?php
App::uses('AppController', 'Controller');
/**
 * CargoNominals Controller
 *
 * @property CargoNominal $CargoNominal
 */
class CargoNominalsController extends AppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->CargoNominal->recursive = 0;
		$this->set('cargoNominals', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->CargoNominal->exists($id)) {
			throw new NotFoundException(__('Invalid cargo nominal'));
		}
		$options = array('conditions' => array('CargoNominal.' . $this->CargoNominal->primaryKey => $id));
		$this->set('cargoNominal', $this->CargoNominal->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->CargoNominal->create();
			if ($this->CargoNominal->save($this->request->data)) {
				$this->Session->setFlash(__('The cargo nominal has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cargo nominal could not be saved. Please, try again.'));
			}
		}
		$tipoCargos = $this->CargoNominal->TipoCargo->find('list');
		$claseCargos = $this->CargoNominal->ClaseCargo->find('list');
		$this->set(compact('tipoCargos', 'claseCargos'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->CargoNominal->exists($id)) {
			throw new NotFoundException(__('Invalid cargo nominal'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CargoNominal->save($this->request->data)) {
				$this->Session->setFlash(__('The cargo nominal has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cargo nominal could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CargoNominal.' . $this->CargoNominal->primaryKey => $id));
			$this->request->data = $this->CargoNominal->find('first', $options);
		}
		$tipoCargos = $this->CargoNominal->TipoCargo->find('list');
		$claseCargos = $this->CargoNominal->ClaseCargo->find('list');
		$this->set(compact('tipoCargos', 'claseCargos'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->CargoNominal->id = $id;
		if (!$this->CargoNominal->exists()) {
			throw new NotFoundException(__('Invalid cargo nominal'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->CargoNominal->delete()) {
			$this->Session->setFlash(__('Cargo nominal deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Cargo nominal was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
