<?php
App::uses('AppController', 'Controller');
/**
 * Territorios Controller
 *
 * @property Territorio $Territorio
 */
class TerritoriosController extends AppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Territorio->recursive = 0;
		$this->set('territorios', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Territorio->exists($id)) {
			throw new NotFoundException(__('Invalid territorio'));
		}
		$options = array('conditions' => array('Territorio.' . $this->Territorio->primaryKey => $id));
		$this->set('territorio', $this->Territorio->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Territorio->create();
			if ($this->Territorio->save($this->request->data)) {
				$this->Session->setFlash(__('The territorio has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The territorio could not be saved. Please, try again.'));
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
		if (!$this->Territorio->exists($id)) {
			throw new NotFoundException(__('Invalid territorio'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Territorio->save($this->request->data)) {
				$this->Session->setFlash(__('The territorio has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The territorio could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Territorio.' . $this->Territorio->primaryKey => $id));
			$this->request->data = $this->Territorio->find('first', $options);
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
		$this->Territorio->id = $id;
		if (!$this->Territorio->exists()) {
			throw new NotFoundException(__('Invalid territorio'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Territorio->delete()) {
			$this->Session->setFlash(__('Territorio deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Territorio was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
