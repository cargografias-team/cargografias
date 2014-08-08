<?php
App::uses('AppController', 'Controller');
/**
 * Cargos Controller
 *
 * @property Cargo $Cargo
 */
class CargosController extends AppController {
    var $uses  = array('Cargo', 'Persona', 'CargoNominal', 'Territorio', 'Partido', 'Fuente');

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Cargo->recursive = 0;
		$this->set('cargos', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Cargo->exists($id)) {
			throw new NotFoundException(__('Invalid cargo'));
		}
		$options = array('conditions' => array('Cargo.' . $this->Cargo->primaryKey => $id));
		$this->set('cargo', $this->Cargo->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Cargo->create();
			if ($this->Cargo->save($this->request->data)) {
				$this->Session->setFlash(__('The cargo has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cargo could not be saved. Please, try again.'));
			}
		}
		$fuentes = $this->Cargo->Fuente->find('list');
		$partidos = $this->Cargo->Partido->find('list');
		$territorios = $this->Cargo->Territorio->find('list');
		$cargoNominals = $this->Cargo->CargoNominal->find('list');
		$motivoFins = $this->Cargo->MotivoFin->find('list');
		$personas = $this->Cargo->Persona->find('list');
		$this->set(compact('fuentes', 'partidos', 'territorios', 'cargoNominals', 'motivoFins', 'personas'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Cargo->exists($id)) {
			throw new NotFoundException(__('Invalid cargo'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Cargo->save($this->request->data)) {
				$this->Session->setFlash(__('The cargo has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cargo could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Cargo.' . $this->Cargo->primaryKey => $id));
			$this->request->data = $this->Cargo->find('first', $options);
		}
		$fuentes = $this->Cargo->Fuente->find('list');
		$partidos = $this->Cargo->Partido->find('list');
		$territorios = $this->Cargo->Territorio->find('list');
		$cargoNominals = $this->Cargo->CargoNominal->find('list');
		$motivoFins = $this->Cargo->MotivoFin->find('list');
		$personas = $this->Cargo->Persona->find('list');
		$this->set(compact('fuentes', 'partidos', 'territorios', 'cargoNominals', 'motivoFins', 'personas'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Cargo->id = $id;
		if (!$this->Cargo->exists()) {
			throw new NotFoundException(__('Invalid cargo'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cargo->delete()) {
			$this->Session->setFlash(__('Cargo deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Cargo was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	

	// 0 Apellido
	// 1 Nombre
	// 2 cargoTipo
	// 3 cargoClase
	// 4 cargoNominal
	// 5 duracionCargo
	// 6 cargoExt
	// 7 territorio
	// 8 territorioExtendido
	// 9 fechaInicioYear
	// 10 fechaInicio
	// 11 fechaFinYear
	// 12 fechaFin
	// 13 partido
	// 14 Declarac. Patrim
	// 15 partido normalizado
        // 16 fuente
        // 17 cargo resumen
        // 18 patrimonio url
	public function admin_import() {
	    $data = array();
		$file_handle = fopen("https://docs.google.com/spreadsheet/pub?key=0Av8QEY2w-qTYdHMxSXVIdVljR25ZMmc4d1ozalMxSXc&single=true&gid=15&output=csv", 'r');
		while (!feof($file_handle) ) {
			$data[] = fgetcsv($file_handle, 1024);
		}
		fclose($file_handle);
		
		//--------------------------------------------------------------------------------------
		
		$iRow = 0;
		$output = array();
		foreach ($data as $row) {
			if ($iRow == 0) { $iRow += 1; continue; } // skip first line

		
			$persona = $this->Persona->find('first', array('conditions' => array('apellido' => $row[0], 'nombre' => $row[1])));
			if ($persona != null) {
				$personaID = $persona['Persona']['persona_id'];
			} else {
				$this->Persona->create();
				$this->Persona->save(array('Persona' => array('nombre' => $row[1], 'apellido' => $row[0] )));
				$personaID = $this->Persona->id;
				$output[] = "Creado " . $row[1] . " " . $row[0];
			}
			

			$this->CargoNominal->recursive = -1;
			$cargoNominal = $this->CargoNominal->find('first', array('conditions' => array('nombre' => $row[4], 'cargo_especifico ' =>  (trim($row[6]) == "" ? null : $row[6]) )));
			if ($cargoNominal == null) {
				//$output[] = "Cargo no encontrado: " . $row[4] . " " . $row[6];
				$output[] = "INSERT INTO cargo_nominal (tipo_cargo_id, clase_cargo_id, nombre, duracion, importancia, cargo_especifico) VALUES(?, ?, '" . $row[4] . "', ?, ?, '" . $row[6] . "');";
				continue;
			}
			$cargoNominalID = $cargoNominal['CargoNominal']['cargo_nominal_id'];
			
			
			$territorio = $this->Territorio->find('first', array('conditions' => array('nombre' => $row[7])));
			if ($territorio == null) {
				$output[] = "Territorio no encontrado: " . $row[7];
				continue;
			}
			$territorioID = $territorio['Territorio']['territorio_id'];
				
			$fuente = $this->Fuente->find('first', array('conditions' => array('nombre' => $row[16])));
			if ($fuente == null) {
				$output[] = "Fuente no encontrado: " . $row[16];
				continue;
			}
			$fuenteID = $fuente['Fuente']['fuente_id'];	
			
			if (trim($row[13]) != "") {
				$partido = $this->Partido->find('first', array('conditions' => array('nombre' => $row[13])));
				if ($partido == null) {
					$output[] = "Partido no encontrado: " . $row[13];
					continue;
				}
				$partidoID = $partido['Partido']['partido_id'];				
			} else {
				$partidoID = null;
			}
			
			
			$fechaInicio = $row[10];
			if (strpos($fechaInicio, "/") === false) {
				$fechaInicio =  $this->getFullFechaInicio($fechaInicio);
			} else {
				$fechaParts = explode("/", $fechaInicio);
				$fechaInicio = $fechaParts[2] . "-" . str_pad($fechaParts[1], 2, "0", STR_PAD_LEFT) . "-" . str_pad($fechaParts[0], 2, "0", STR_PAD_LEFT);
			}
			
			
			if (trim($row[12]) != "") {
				$fechaFin = $row[12];
				if (strpos($fechaFin, "/") === false) {
					$fechaFin =  $this->getFullFechaInicio($fechaFin);
				} else {
					$fechaParts = explode("/", $fechaFin);
					$fechaFin = $fechaParts[2] . "-" . str_pad($fechaParts[1], 2, "0", STR_PAD_LEFT) . "-" . str_pad($fechaParts[0], 2, "0", STR_PAD_LEFT);
				}			
			} else {
				$fechaFin = null;
			}

			$this->Cargo->recursive = -1;
			$cargo = $this->Cargo->find('first', array('conditions' => array('persona_id' => $personaID, 'cargo_nominal_id' => $cargoNominalID, 'fecha_inicio' => $fechaInicio)));
			if ($cargo != null) {
				// This doesn't work. Fuck it. Let someone else do it.
				//$cargoID = $cargo['Cargo']['cargo_id'];
				//$cargoData = array('territorio_id' => $territorioID);
				//if ($partidoID != null) { $cargoData['partido_id'] = $partidoID; }
				//if ($fechaFin != null) { $cargoData['fecha_fin'] = $fechaFin; }
				//$this->Cargo->save(array('Cargo' => $cargoData));
				//$output[] = "Update cargo: " . $cargoID . " - " . $row[1] . " " . $row[0] . " - " . $row[4] . " " . $row[6] . " - " . $fechaInicio;
			} else {
				$this->Cargo->create();
				$cargoData = array('fuente_id' => $fuenteID, 'fecha_inicio' => $fechaInicio, 'territorio_id' => $territorioID, 'cargo_nominal_id' => $cargoNominalID, 'persona_id' => $personaID );
				if ($partidoID != null) { $cargoData['partido_id'] = $partidoID; }
				if ($fechaFin != null) { $cargoData['fecha_fin'] = $fechaFin; }
				$this->Cargo->save(array('Cargo' => $cargoData));
				$cargoID = $this->Cargo->id;
				$output[] = "Created cargo: " . $cargoID . " - " . $row[1] . " " . $row[0] . " - " . $row[4] . " " . $row[6] . " - " . $fechaInicio;
			}
			
			
			// TODO: Patrimonios
			
			$iRow += 1;
		}
			
		//--------------------------------------------------------------------------------------
		
		$this->set('csv_data', $output);
	}
	
	
	//==========================================================================
	//==========================================================================
	//==========================================================================
	
	// Duplicated from ApiController. This is THE HORROR
	// Clean this shit up.
	function getFullFechaInicio($y) {

		$fields = array(
			'Cargo.fecha_inicio',
			'count(1) as c'
		);

		$conditions = array(
			'YEAR(Cargo.fecha_inicio)' => $y
		);

		$group = array(
			'Cargo.fecha_inicio'
		);

		$order = array(
			'c' => 'DESC'
		);

		$res = $this->Cargo->find('first', array('conditions'=> $conditions,'group'=> $group,'order'=> $order,'fields'=> $fields) );

		if(count($res)>0){
			$data['fecha'] = $res['Cargo']['fecha_inicio']; 
		}else{
			$data['fecha'] = $y."-01-01";		
		}

		return $data['fecha'];
	}
	
	//==========================================================================
	//==========================================================================
	//==========================================================================
}
