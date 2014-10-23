<?php
App::uses('AppModel', 'Model');
/**
 * Role Model
 *
 * @property User $User
 */
class Role extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Role title is required',
				'allowEmpty' => false,
				'required' => false
			),
		),
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
		'Acl' => array(
			'type' => 'requester'
		),
		'Sluggable' => array(
			'field' => 'title'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'role_id',
			'order' => 'User.username ASC'
		),
		'Log' => array(
			'className' => 'Log',
			'foreignKey' => 'model_id',
			'conditions' => 'Log.model = "Role"',
			'order' => 'Log.created DESC'
		)
	);

/**
 * parentNode method
 *
 * @return array
 */
	public function parentNode() {
		return null;
	}

}
