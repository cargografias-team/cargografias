<?php
App::uses('AppController', 'Controller');
class BlogController extends AppController {

	public function beforeFilter() {
		$this->Auth->allow('index');
		parent::beforeFilter();
	}

	public function index() {
	}

}

?>