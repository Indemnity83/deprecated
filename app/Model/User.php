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
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Username is required',
				'allowEmpty' => false,
				'required' => true
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'Username is already taken'
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
				'allowEmpty' => false,
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
 * Constructor
 *
 * @param bool|string $id ID
 * @param string $table Table
 * @param string $ds Datasource
 */
	public function __construct($id = false, $table = null, $ds = null) {
		$this->_setupValidation();
		parent::__construct($id, $table, $ds);
	}

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

/**
 * Setup validation rules
 *
 * @return void
 */
	protected function _setupValidation() {
		$this->validatePasswordChange = array(
			'new_password' => $this->validate['password'],
			'confirm_password' => array(
				'required' => array('rule' => array('compareFields', 'new_password', 'confirm_password'), 'required' => true, 'message' => __d('users', 'The passwords are not equal.'))),
			'old_password' => array(
				'to_short' => array('rule' => 'validateOldPassword', 'required' => true, 'message' => __d('users', 'Invalid password.'))
			)
		);
	}

/**
 * Validation method to check the old password
 *
 * @param array $password to validate
 * @throws OutOfBoundsException
 * @return bool True on success
 */
	public function validateOldPassword($password) {
		if (!isset($this->data[$this->alias]['id']) || empty($this->data[$this->alias]['id'])) {
			if (Configure::read('debug') > 0) {
				throw new OutOfBoundsException(__d('users', '$this->data[\'' . $this->alias . '\'][\'id\'] has to be set and not empty'));
			}
		}

		$passwordHasher = new BlowfishPasswordHasher();
		$currentPassword = $this->field('password', array($this->alias . '.id' => $this->data[$this->alias]['id']));
		return $currentPassword === $passwordHasher->hash($password['old_password']);
	}

/**
 * Changes the password for a user
 *
 * @param array $postData Post data from controller
 * @return bool True on success
 */
	public function changePassword($postData = array()) {
		$this->validate = $this->validatePasswordChange;

		$this->set($postData);
		if ($this->validates()) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['new_password']);
			$this->save($postData, array(
				'validate' => false,
				'callbacks' => false));
			return true;
		}
		return false;
	}

/**
 * Validation method to compare two fields
 *
 * @param mixed $field1 Array or string, if array the first key is used as fieldname
 * @param string $field2 Second fieldname
 * @return bool True on success
 */
	public function compareFields($field1, $field2) {
		if (is_array($field1)) {
			$field1 = key($field1);
		}

		if (isset($this->data[$this->alias][$field1]) && isset($this->data[$this->alias][$field2]) &&
			$this->data[$this->alias][$field1] == $this->data[$this->alias][$field2]) {
			return true;
		}
		return false;
	}

}
