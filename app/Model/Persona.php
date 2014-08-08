<?php
App::uses('AppModel', 'Model');
/**
 * Persona Model
 *
 * @property Cargo $Cargo
 * @property Patrimonio $Patrimonio
 */
class Persona extends AppModel {

	public $actsAs = array('Containable');

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'persona';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'persona_id';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nombre_completo';

public $virtualFields = array(
    'nombre_completo' => 'CONCAT(Persona.nombre, " ", Persona.apellido)'
);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'persona_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'nombre' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'apellido' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Cargo' => array(
			'className' => 'Cargo',
			'foreignKey' => 'persona_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => 'fecha_inicio',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Patrimonio' => array(
			'className' => 'Patrimonio',
			'foreignKey' => 'persona_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public $hasOne = array(
		'HallOfFameStat' => array(
			'className' => 'HallOfFameStat',
			'foreignKey' => 'persona_id',
		),
	);

}
