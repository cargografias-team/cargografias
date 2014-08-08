<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components = array(
		'Session',
		'Auth' => array(
			'loginAction' => 'login',
			'logoutRedirect' => 'login',
	        'authError' => 'Did you really think you are allowed to see that?',
	        'authenticate' => array('Form')
		)
	);

	public $helpers = array(
		'Chosen.Chosen',
	);

	function beforeFilter() {
        if ((isset($this->params['prefix']) && $this->params['prefix'] == 'admin') || $this->params['action'] == 'login' ) {
            $this->layout = 'admin';
        } else {
            $this->layout = 'publico';
        }

        if ($this->Auth->user()) {
			$this->set('authUser', $this->Auth->user());
        }

        //Selected IDS
   		$this->selectedIds = array();
        foreach ($this->params['pass'] as $key => $value) {
        	if(is_numeric($value)){
        		$this->selectedIds[] = (int)$value;
        	}
        }

    }

	function dispatchJson($data,$method='get_quiz'){
		$this->autoLayout = $this->autoRender = false;
		$this->layout = 'ajax';
		$this->set(compact('data'));
		$this->render('/Layouts/'.$method);
	}

}
