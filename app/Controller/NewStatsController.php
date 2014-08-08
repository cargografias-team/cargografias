<?php
App::uses('AppController', 'Controller');

class NewstatsController extends AppController {


	public function beforeFilter() {
		$this->Auth->allow('home');
		parent::beforeFilter();
	}

	public function home() {
		$this->layout = 'publico-v2';

	}

}
