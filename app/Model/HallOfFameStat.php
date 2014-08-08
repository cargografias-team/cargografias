<?php
App::uses('AppModel', 'Model');
/**
 * Hito Model
 *
 */
class HallOfFameStat extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'hall_of_fame_stat';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'persona_id';

/**
 * Behaviors
 */
	public $actsAs = array('Containable');
}
