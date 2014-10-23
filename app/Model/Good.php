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
			),
		),
		'fluid_ounces' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Fl. oz must be numeric',
				'allowEmpty' => false,
				'required' => true
			),
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

/*
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
		)
	);

}
