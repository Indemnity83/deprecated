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
			'passwordsMatch' => array(
				'rule' => array('equaltofield', 'password_match'),
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
		'Acl' => array(
			'type' => 'requester'
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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
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
			$this->data[$this->alias]['password'] = $passwordHasher->hash(
				$this->data[$this->alias]['password']
			);
		}
		if (!isset($this->data[$this->alias]['role_id'])) {
			$role = $this->Role->findByTitle('User');
			$this->data[$this->alias]['role_id'] = $role['Role']['id'];
		}
		return true;
	}

/**
 * equaltofield method
 *
 * @param string $check first field to check
 * @param string $otherfield second field to check
 * @return bolean
 */
	public function equaltofield($check, $otherfield) {
		//get name of field
		$fname = '';
		foreach ($check as $key => $value) {
			$fname = $key;
			break;
		}
		return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname];
	}

/**
 * parentNode method
 *
 * @return array
 */
	public function parentNode() {
		if (!$this->id && empty($this->data)) {
			return null;
		}
		if (isset($this->data['User']['role_id'])) {
			$roleId = $this->data['User']['role_id'];
		} else {
			$roleId = $this->field('role_id');
		}
		if (!$roleId) {
			return null;
		} else {
			return array('Role' => array('id' => $roleId));
		}
	}

}
