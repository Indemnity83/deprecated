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
		),
		'quantity' => array(
			'non-zero' => array(
				'rule' => array('range', 0, 1000),
				'message' => 'Quantity must greater than zero and less than 1000',
				'allowEmpty' => false,
				'required' => true
			)
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

/**
 * checks if a consumption record is owned by the given user
 * 
 * @param string $consumption the consumption id to check
 * @param string $user the user id to check
 * @return bool
 */
	public function isOwnedBy($consumption, $user) {
		return $this->field('id', array('id' => $consumption, 'user_id' => $user)) !== false;
	}

/**
 * Called before each save operation, after validation. Return a non-true result
 * to halt the save.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if the operation should continue, false if it should abort
 */
	public function beforeSave($options = array()) {
		if (!empty($this->data['Consumption']['when']) ) {
			$this->data['Consumption']['when'] = $this->dateFormatBeforeSave($this->data['Consumption']['when']);
		}
		return true;
	}

/**
 * format date before saving
 * 
 * @param string $dateString unformatted date string
 * @return string
 */
	public function dateFormatBeforeSave($dateString) {
		return date('Y-m-d', strtotime($dateString));
	}

/**
 * Called after each find operation. Can be used to modify any results returned by find().
 * Return value should be the (modified) results.
 *
 * @param mixed $results The results of the find operation
 * @param bool $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed Result of the find operation
 */
	public function afterFind($results, $primary = false) {
		foreach ($results as $key => $val) {
			if (isset($val['Consumption']['when'])) {
				$results[$key]['Consumption']['when'] = $this->dateFormatAfterFind($val['Consumption']['when']);
			}
		}
		return $results;
	}

/**
 * format date after finding
 * 
 * @param string $dateString unformatted date string
 * @return string
 */
	public function dateFormatAfterFind($dateString) {
			return date('Y-m-d', strtotime($dateString));
	}
}
