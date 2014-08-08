<?php
App::uses('AppController', 'Controller');


class CargografiasController extends AppController {

	var $uses = array('Hito','CargoNominal','Partido','Territorio');

	public function beforeFilter() {
		$this->Auth->allow('view');
		parent::beforeFilter();
	}

	public function view($key) {
		switch ($key) {

			case 'trivia':
				$this->render('trivia');
				break;
			
			case 'linea-de-tiempo':
				$this->set('cargos', $this->CargoNominal->find('list'));
				$this->set('partidos', $this->Partido->find('list'));
				$this->set('territorios', $this->Territorio->find('list'));
				$this->render('linea-de-tiempo');
				break;

			case 'linea-de-tiempo-legacy':
				$this->render('linea-de-tiempo-legacy');
				break;	

			case 'presidenciables-chile':
				$this->render('presidenciables-chile');
				break;

			case 'hitos-historicos':
				$options = array(
			        'fields' => array('Hito.fecha', 'Hito.titulo'),
			        'order' => array('Hito.fecha')
			    );
			    $hitos = $this->Hito->find('list',$options);
				$this->set('hitos', array(''.date('Y-m-d')=>'ACTUALIDAD') + $hitos);
				$this->render('hitos-historicos');
				break;

			default:
				$this->Session->setFlash('La visualización seleccionada no existe.');
				$this->redirect('/');
				break;
		}
	}

}
