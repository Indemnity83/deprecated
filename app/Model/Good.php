<?php
App::uses('AppModel', 'Model');
/**
 * Good Model
 *
 */
class Good extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Name is required',
				'allowEmpty' => false,
				'required' => true
			),
		),
		'caffeine_level' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Caffeine level must be numeric',
				'allowEmpty' => false,
				'required' => true
			)
		),
		'per' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Fl. oz must be numeric',
				'allowEmpty' => false,
				'required' => true
			),
			'non-zero' => array(
				'rule' => array('range', 0, 1000),
				'message' => 'Fl. oz must greater than zero and less than 1000'
			)
		),
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Consumption' => array(
			'className' => 'Consumption',
			'foreignKey' => 'good_id',
			'order' => 'Consumption.when ASC'
		),
		'Log' => array(
			'className' => 'Log',
			'foreignKey' => 'model_id',
			'conditions' => 'Log.model = "Good"',
			'order' => 'Log.created DESC'
		)
	);

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Logable' => array(
			'change' => 'full',
		),
		'Sluggable' => array(
			'field' => 'name'
		),
		'Enumerable'
	);

/**
 * Constants
 *
 * @var string
 */
	const UNIT_FLOZ = 0;
	const UNIT_TABLET = 1;
	const UNIT_DOSE = 2;
	const UNIT_PACK = 3;
	const UNIT_PIECE = 4;

/**
 * enums
 *
 * @var array
 */
	public $enum = array(
		'unit' => array(
			self::UNIT_FLOZ => 'fl. oz',
			self::UNIT_TABLET => 'tablet',
			self::UNIT_DOSE => 'dose',
			self::UNIT_PACK => 'package',
			self::UNIT_PIECE => 'piece'
		)
	);


}
