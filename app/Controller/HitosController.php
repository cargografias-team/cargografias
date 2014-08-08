<?php
App::uses('AppController', 'Controller');
/**
 * Hitos Controller
 *
 * @property Hito $Hito
 */
class HitosController extends AppController {

	public function beforeFilter() {
		$this->Auth->allow(array('view'));
		parent::beforeFilter();
	}

    public function view($date) {
        $data = $this->Hito->findByFecha($date);
        if(isset($data['Hito']))
        	$data = $data['Hito'];
        $this->dispatchJson($data);
    }

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Hito->recursive = 0;
		$this->set('hitos', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Hito->exists($id)) {
			throw new NotFoundException(__('Invalid hito'));
		}
		$options = array('conditions' => array('Hito.' . $this->Hito->primaryKey => $id));
		$this->set('hito', $this->Hito->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Hito->create();
			if ($this->Hito->save($this->request->data)) {
				$this->Session->setFlash(__('The hito has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The hito could not be saved. Please, try again.'));
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
		if (!$this->Hito->exists($id)) {
			throw new NotFoundException(__('Invalid hito'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Hito->save($this->request->data)) {
				$this->Session->setFlash(__('The hito has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The hito could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Hito.' . $this->Hito->primaryKey => $id));
			$this->request->data = $this->Hito->find('first', $options);
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
		$this->Hito->id = $id;
		if (!$this->Hito->exists()) {
			throw new NotFoundException(__('Invalid hito'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Hito->delete()) {
			$this->Session->setFlash(__('Hito deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Hito was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
