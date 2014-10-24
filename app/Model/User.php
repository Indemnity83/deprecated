<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
/**
 * User Model
 *
 * @property Log $Log
 * @property Time $Time
 * @property Group $Group
 */
class User extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => false, 'allowEmpty' => false,
				'message' => 'Please enter a username.'
			),
			'alpha' => array(
				'rule' => array('alphaNumeric'),
				'message' => 'The username must be alphanumeric.'
			),
			'unique_username' => array(
				'rule' => array('isUnique', 'username'),
				'message' => 'This username is already in use.'
			),
			'username_min' => array(
				'rule' => array('minLength', '3'),
				'message' => 'The username must have at least 3 characters.'
			)
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Valid email is required',
				'allowEmpty' => false,
				'required' => false
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'Email address is already taken'
			)
		),
		'password' => array(
			'too_short' => array(
				'rule' => array('minLength', '6'),
				'message' => 'The password must have at least 6 characters.',
				'allowEmpty' => false,
				'required' => true,
				'on' => 'create'
			),
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a password.'
			)
		),
		'temppassword' => array(
			'passwordsMatch' => array(
				'rule' => 'confirmPassword',
				'message' => 'Passwords do not match',
				'allowEmpty' => true,
				'required' => true,
				'on' => 'create'
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
		),
		'Enumerable'
	);

/**
 * Constants
 *
 * @var string
 */
	const ROLE_USER = 0;
	const ROLE_ADMIN = 1;
	const ROLE_TRUSTED = 2;

/**
 * enums
 *
 * @var array
 */
	public $enum = array(
		'role' => array(
			self::ROLE_USER => 'user',
			self::ROLE_ADMIN => 'admin',
			self::ROLE_TRUSTED => 'trusted'
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
			'conditions' => 'Log.model = "User"',
			'order' => 'Log.created DESC'
		),
		'Action' => array(
			'className' => 'Log',
			'foreignKey' => 'user_id',
			'order' => 'Action.created DESC'
		),
		'Consumption' => array(
			'className' => 'Consumption',
			'foreignKey' => 'user_id',
			'order' => 'Consumption.when ASC'
		)
	);

/**
 * beforeSave method
 *
 * @param array $options Options passed from Model::save().
 * @return bolean
 */
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
		}
		if (!isset($this->data[$this->alias]['role'])) {
			$this->data[$this->alias]['role'] = self::ROLE_USER;
		}
		return true;
	}

/**
 * Custom validation method to ensure that the two entered passwords match
 *
 * @param string $password Password
 * @return bool Success
 */
	public function confirmPassword($password = null) {
		if ((isset($this->data[$this->alias]['password']) && isset($password['temppassword']))
			&& !empty($password['temppassword'])
			&& ($this->data[$this->alias]['password'] === $password['temppassword'])) {
			return true;
		}
		return false;
	}

}
