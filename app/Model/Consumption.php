<?php
App::uses('AppModel', 'Model');
/**
 * consumption Model
 *
 * @property User $User
 * @property Good $Good
 */
class Consumption extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'when' => array(
			'datetime' => array(
				'rule' => array('date', 'ymd'),
				'message' => 'Enter a valid date in MM/DD/YY format',
				'allowEmpty' => false,
				'required' => true
			),
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
		)
	);	

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Good' => array(
			'className' => 'Good',
			'foreignKey' => 'good_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Log' => array(
			'className' => 'Log',
			'foreignKey' => 'model_id',
			'conditions' => 'Log.model = "Consumption"',
			'order' => 'Log.created DESC'
		)
	);		

/*
 * BeforeSave Function
 *
 */
	public function beforeSave($options = array()) {
		if (!empty($this->data['Consumption']['when']) ) {
			$this->data['Consumption']['when'] = $this->dateFormatBeforeSave($this->data['Consumption']['when']);
		}
		return true;
	}

/*
 * dateFormatBeforeSave Function
 *
 */
	public function dateFormatBeforeSave($dateString) {
		return date('Y-m-d', strtotime($dateString));
	}

/*
 * BeforeSave Function
 *
 */
	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['Consumption']['when'])) {
				$results[$key]['Consumption']['when'] = $this->dateFormatAfterFind($val['Consumption']['when']);
			}
		}
		return $results;
	}

/*
 * dateFormatBeforeSave Function
 *
 */
	public function dateFormatAfterFind($dateString) {
		return date('Y-m-d', strtotime($dateString));
	}	
}
