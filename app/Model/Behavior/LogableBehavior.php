<?php

/**
 * Logs saves and deletes of any model
 *
 * Requires the following to work as intended :
 *
 * - "Log" model ( empty but for a order variable [created DESC]
 * - "logs" table with these fields required :
 * - id			[int]			:
 * - title 		[string] 		: automagically filled with the display field of the model that was modified.
 * - created	[date/datetime] : filled by cake in normal way
 *
 * - actsAs = array("Logable"); on models that should be logged
 *
 * Optional extra table fields for the "logs" table :
 *
 * - "description" 	[string] : Fill with a descriptive text of what, who and to which model/row :
 * "Contact "John Smith"(34) added by User "Administrator"(1).
 *
 * or if u want more detail, add any combination of the following :
 *
 * - "model"    	[string] : automagically filled with the class name of the model that generated the activity.
 * - "model_id" 	[int]	 : automagically filled with the primary key of the model that was modified.
 * - "action"   	[string] : automagically filled with what action is made (add/edit/delete)
 * - "user_id"  	[int]    : populated with the supplied user info. (May be renamed. See bellow.)
 * - "change"   	[string] : depending on setting either :
 * [name (alek) => (Alek), age (28) => (29)] or [name, age]
 *
 * - "version_id"	[int]	 : cooperates with RevisionBehavior to link the the shadow table (thus linking to old data)
 *
 * Remember that Logable behavior needs to be added after RevisionBehavior. In fact, just put it last to be safe.
 *
 * Optionally register what user was responisble for the activity :
 *
 * - Supply configuration only if defaults are wrong. Example given with defaults :
 *
 * class Apple extends AppModel {
 * var $name = 'Apple';
 * var $actsAs = array('Logable' => array('userModel' => 'User', 'userKey' => 'user_id'));
 * [..]
 *
 * - In AppController (or single controller if only needed once) add these lines to beforeFilter :
 *
 * if (sizeof($this->uses) && $this->{$this->modelClass}->Behaviors->attached('Logable')) {
 * $this->{$this->modelClass}->setUserData($this->activeUser);
 * }
 *
 * This is not used any longer, as AuthComponent collect the user data instead.
 *
 * Where "$activeUser" should be an array in the standard format for the User model used :
 *
 * $activeUser = array( $UserModel->alias => array( $UserModel->primaryKey => 123, $UserModel->displayField => 'Alexander'));
 * // any other key is just ignored by this behaviour.
 *
 * @author Alexander Morland (alexander#maritimecolours.no)
 * @co-author Eskil Mjelva Saatvedt
 * @co-author Ronny Vindenes
 * @co-author Carl Erik Fyllingen
 * @contributor Miha
 * @category Behavior
 * @version 2.3
 * @modified 15.november 2011 by Eskil
 */

class LogableBehavior extends ModelBehavior {

	public $user = null;

	public $UserModel = false;

	public $settings = array();

	public $defaults = array(
			'enabled' => true,
			'userModel' => 'User',
			'userKey' => 'user_id',
			'change' => 'list',
			'description_ids' => true,
			'skip' => array(),
			'ignore' => array(),
			'classField' => 'model',
			'foreignKey' => 'model_id');

	public $schema = array();

/**
 * Cake called intializer
 * Config options are :
 * userModel 		: 'User'. Class name of the user model you want to use (User by default), if you want to save User in log
 * userKey   		: 'user_id'. The field for saving the user to (user_id by default).
 * change    		: 'list' > [name, age]. Set to 'full' for [name (alek) => (Alek), age (28) => (29)]
 * description_ids 	: true. Set to false to not include model id and user id in the title field
 * skip  			: array(). String array of actions to not log
 *
 * @param Model $Model using this behavior
 * @param array $config array of configuration settings
 * @return null
 */
	public function setup(Model $Model, $config = array()) {
		if (!is_array($config)) {
			$config = array();
		}
		$this->settings[$Model->alias] = array_merge($this->defaults, $config);
		$this->settings[$Model->alias]['ignore'][] = $Model->primaryKey;

		$this->Log = ClassRegistry::init('Log');
		if ($this->settings[$Model->alias]['userModel'] != $Model->alias) {
			$this->UserModel = ClassRegistry::init($this->settings[$Model->alias]['userModel']);
		} else {
			$this->UserModel = $Model;
		}
		$this->schema = $this->Log->schema();
		App::import('Component', 'Auth');
		$this->user[$this->settings[$Model->alias]['userModel']] = AuthComponent::user();
	}

/**
 * get behavior settings
 * 
 * @param Model &$Model using this behavior
 * @return array
 */
	public function settings(&$Model) {
		return $this->settings[$Model->alias];
	}

/**
 * enable/disable logging for a model 
 * 
 * @param Model &$Model using this behavior
 * @param bool $enable logging for this model
 * @return bool
 */
	public function enableLog(&$Model, $enable = null) {
		if ($enable !== null) {
			$this->settings[$Model->alias]['enabled'] = $enable;
		}
		return $this->settings[$Model->alias]['enabled'];
	}

/**
 * Useful for getting logs for a model, takes params to narrow find.
 * This method can actually also be used to find logs for all models or
 * even another model. Using no params will return all activities for
 * the models it is called from.
 *
 * Possible params :
 * 'model' 		: mixed  (null) String with className, null to get current or false to get everything
 * 'action' 	: string (null) String with action (add/edit/delete), null gets all
 * 'order' 		: string ('created DESC') String with custom order
 * 'conditions  : array  (array()) Add custom conditions
 * 'model_id'	: int	 (null) Add a int
 *
 * (remember to use your own user key if you're not using 'user_id')
 * 'user_id' 	: int 	 (null) Defaults to all users, supply id if you want for only one User
 *
 * @param Model &$Model using this behavior
 * @param array $params to search for
 * @return array
 */
	public function findLog(&$Model, $params = array()) {
		$defaults = array(
				$this->settings[$Model->alias]['classField'] => null,
				'action' => null,
				'order' => 'created DESC',
				$this->settings[$Model->alias]['userKey'] => null,
				'conditions' => array(),
				$this->settings[$Model->alias]['foreignKey'] => null,
				'fields' => array(),
				'limit' => 50);
		$params = array_merge($defaults, $params);
		$options = array(
				'order' => $params['order'],
				'conditions' => $params['conditions'],
				'fields' => $params['fields'],
				'limit' => $params['limit']);
		if ($params[$this->settings[$Model->alias]['classField']] === null) {
			$params[$this->settings[$Model->alias]['classField']] = $Model->alias;
		}
		if ($params[$this->settings[$Model->alias]['classField']]) {
			if (isset($this->schema[$this->settings[$Model->alias]['classField']])) {
				$options['conditions'][$this->settings[$Model->alias]['classField']] = $params[$this->settings[$Model->alias]['classField']];
			} elseif (isset($this->schema['description'])) {
				$options['conditions']['description LIKE '] = $params[$this->settings[$Model->alias]['classField']] . '%';
			} else {
				return false;
			}
		}
		if ($params['action'] && isset($this->schema['action'])) {
			$options['conditions']['action'] = $params['action'];
		}
		if ($params[$this->settings[$Model->alias]['userKey']] && $this->UserModel && is_numeric($params[$this->settings[$Model->alias]['userKey']])) {
			$options['conditions'][$this->settings[$Model->alias]['userKey']] = $params[$this->settings[$Model->alias]['userKey']];
		}
		if ($params[$this->settings[$Model->alias]['foreignKey']] && is_numeric($params[$this->settings[$Model->alias]['foreignKey']])) {
			$options['conditions'][$this->settings[$Model->alias]['foreignKey']] = $params[$this->settings[$Model->alias]['foreignKey']];
		}
		return $this->Log->find('all', $options);
	}

/**
 * Get list of actions for one user.
 * Params for getting (one line) activity descriptions
 * and/or for just one model
 *
 * @param Model &$Model using this behavior
 * @param int $userId id of user
 * @param array $params to search for
 * @return array
 * @example $this->Model->findUserActions(301, array('model' => 'BookTest'));
 * @example $this->Model->findUserActions(301, array('events' => true));
 * @example $this->Model->findUserActions(301, array('fields' => array('id','model'),'model' => 'BookTest');
 */
	public function findUserActions(&$Model, $userId, $params = array()) {
		if (!$this->UserModel) {
			return null;
		}
		// if logged in user is asking for her own log, use the data we allready have
		if (isset($this->user) && isset($this->user[$this->UserModel->alias][$this->UserModel->primaryKey]) && $userId == $this->user[$this->UserModel->alias][$this->UserModel->primaryKey] && isset($this->user[$this->UserModel->alias][$this->UserModel->displayField])) {
			$username = $this->user[$this->UserModel->alias][$this->UserModel->displayField];
		} else {
			$this->UserModel->recursive = -1;
			$user = $this->UserModel->find(array(
					$this->UserModel->primaryKey => $userId));
			$username = $user[$this->UserModel->alias][$this->UserModel->displayField];
		}
		$fields = array();
		if (isset($params['fields'])) {
			if (is_array($params['fields'])) {
				$fields = $params['fields'];
			} else {
				$fields = array(
						$params['fields']);
			}
		}
		$conditions = array(
				$this->settings[$Model->alias]['userKey'] => $userId);
		if (isset($params[$this->settings[$Model->alias]['classField']])) {
			$conditions[$this->settings[$Model->alias]['classField']] = $params[$this->settings[$Model->alias]['classField']];
		}
		$data = $this->Log->find('all', array(
				'conditions' => $conditions,
				'recursive' => -1,
				'fields' => $fields));
		if (!isset($params['events']) || ( isset($params['events']) && $params['events'] == false )) {
			return $data;
		}
		$result = array();
		foreach ($data as $key => $row) {
			$one = $row['Log'];
			$result[$key]['Log']['id'] = $one['id'];
			$result[$key]['Log']['event'] = $username;
			// have all the detail models and change as list :
			if (isset($one[$this->settings[$Model->alias]['classField']]) && isset($one['action']) && isset($one['change']) && isset($one[$this->settings[$Model->alias]['foreignKey']])) {
				if ($one['action'] == 'edit') {
					$result[$key]['Log']['event'] .= ' edited ' . $one['change'] . ' of ' . low($one[$this->settings[$Model->alias]['classField']]) . '(id ' . $one[$this->settings[$Model->alias]['foreignKey']] . ')';
				} elseif ($one['action'] == 'add') {
					$result[$key]['Log']['event'] .= ' added a ' . low($one[$this->settings[$Model->alias]['classField']]) . '(id ' . $one[$this->settings[$Model->alias]['foreignKey']] . ')';
				} elseif ($one['action'] == 'delete') {
					$result[$key]['Log']['event'] .= ' deleted the ' . low($one[$this->settings[$Model->alias]['classField']]) . '(id ' . $one[$this->settings[$Model->alias]['foreignKey']] . ')';
				}

			} elseif (isset($one[$this->settings[$Model->alias]['classField']]) && isset($one['action']) && isset($one[$this->settings[$Model->alias]['foreignKey']])) { // have model,model_id and action
				if ($one['action'] == 'edit') {
					$result[$key]['Log']['event'] .= ' edited ' . low($one[$this->settings[$Model->alias]['classField']]) . '(id ' . $one[$this->settings[$Model->alias]['foreignKey']] . ')';
				} elseif ($one['action'] == 'add') {
					$result[$key]['Log']['event'] .= ' added a ' . low($one[$this->settings[$Model->alias]['classField']]) . '(id ' . $one[$this->settings[$Model->alias]['foreignKey']] . ')';
				} elseif ($one['action'] == 'delete') {
					$result[$key]['Log']['event'] .= ' deleted the ' . low($one[$this->settings[$Model->alias]['classField']]) . '(id ' . $one[$this->settings[$Model->alias]['foreignKey']] . ')';
				}
			} else { // only description field exist
				$result[$key]['Log']['event'] = $one['description'];
			}

		}
		return $result;
	}

/**
 * Use this to supply a model with the data of the logged in User.
 * Intended to be called in AppController::beforeFilter like this :
 *
 * if ($this->{$this->modelClass}->Behaviors->attached('Logable')) {
 * $this->{$this->modelClass}->setUserData($activeUser);/
 * }
 *
 * @param Model &$Model using this behavior
 * @param array $userData expected to look like the result of a User::find(array('id'=>123));
 * @return null
 */
	public function setUserData(&$Model, $userData = null) {
		if ($userData) {
			$this->user = $userData;
		}
	}

/**
 * Used for logging custom actions that arent crud, like login or download.
 *
 * @param Model &$Model using this behavior
 * @param string $action name of action that is taking place (dont use the crud ones)
 * @param int $id  id of the logged item (ie model_id in logs table)
 * @param array $values optional other values for your logs table
 * @return null
 * @example $this->Boat->customLog('ship', 66, array('title' => 'Titanic heads out'));
 */
	public function customLog(&$Model, $action, $id, $values = array()) {
		$logData['Log'] = $values;
		if (isset($this->schema[$this->settings[$Model->alias]['foreignKey']]) && is_numeric($id)) {
			$logData['Log'][$this->settings[$Model->alias]['foreignKey']] = $id;
		}
		$title = null;
		if (isset($values['title'])) {
			$title = $values['title'];
			unset($logData['Log']['title']);
		}
		$logData['Log']['action'] = $action;
		$this->__saveLog($Model, $logData, $title);
	}

/**
 * clears user data
 * 
 * @param Model &$Model using this behavior
 * @return null
 */
	public function clearUserData(&$Model) {
		$this->user = null;
	}

/**
 * Setter for userIP
 * 
 * @param Model &$Model using this behavior
 * @param string $userIP to be set
 * @return null
 */
	public function setUserIp(&$Model, $userIP = null) {
		$this->userIP = $userIP;
	}

/**
 * Before delete is called before any delete occurs on the attached model, but after the model's
 * beforeDelete is called. Returning false from a beforeDelete will abort the delete.
 *
 * @param Model $Model Model using this behavior
 * @param bool $cascade If true records that depend on this record will also be deleted
 * @return mixed false if the operation should abort. Any other result will continue.
 */
	public function beforeDelete(Model $Model, $cascade = true) {
		if (!$this->settings[$Model->alias]['enabled']) {
			return true;
		}
		if (isset($this->settings[$Model->alias]['skip']['delete']) && $this->settings[$Model->alias]['skip']['delete']) {
			return true;
		}
		$Model->recursive = -1;
		$Model->read();
		return true;
	}

/**
 * After delete is called after any delete occurs on the attached model.
 *
 * @param Model $Model Model using this behavior
 * @return void
 */
	public function afterDelete(Model $Model) {
		if (!$this->settings[$Model->alias]['enabled']) {
			return true;
		}
		if (isset($this->settings[$Model->alias]['skip']['delete']) && $this->settings[$Model->alias]['skip']['delete']) {
			return true;
		}
		$logData = array();
		if (isset($this->schema['description'])) {
			$logData['Log']['description'] = $Model->alias;
			if (isset($Model->data[$Model->alias][$Model->displayField]) && $Model->displayField != $Model->primaryKey) {
				$logData['Log']['description'] .= ' "' . $Model->data[$Model->alias][$Model->displayField] . '"';
			}
			if ($this->settings[$Model->alias]['description_ids']) {
				$logData['Log']['description'] .= ' (' . $Model->id . ') ';
			}
			$logData['Log']['description'] .= __('deleted', true);
		}
		$logData['Log']['action'] = 'delete';
		$this->__saveLog($Model, $logData);
	}

/**
 * beforeSave is called before a model is saved. Returning false from a beforeSave callback
 * will abort the save operation.
 *
 * @param Model $Model Model using this behavior
 * @param array $options Options passed from Model::save().
 * @return mixed false if the operation should abort. Any other result will continue.
 * @see Model::save()
 */
	public function beforeSave(Model $Model, $options = array()) {
		if (isset($this->schema['change']) && $Model->id) {
			$this->old = $Model->find('first', array(
					'conditions' => array(
							$Model->alias . '.' . $Model->primaryKey => $Model->id),
					'recursive' => -1));
		}
		return true;
	}

/**
 * afterSave is called after a model is saved.
 *
 * @param Model $Model Model using this behavior
 * @param bool $created true if this save created a new record
 * @param array $options Options passed from Model::save().
 * @return bool
 * @see Model::save()
 */
	public function afterSave(Model $Model, $created, $options = array()) {
		if (!$this->settings[$Model->alias]['enabled']) {
			return true;
		}
		if (isset($this->settings[$Model->alias]['skip']['add']) && $this->settings[$Model->alias]['skip']['add'] && $created) {
			return true;
		} elseif (isset($this->settings[$Model->alias]['skip']['edit']) && $this->settings[$Model->alias]['skip']['edit'] && !$created) {
			return true;
		}
		$keys = array_keys($Model->data[$Model->alias]);
		$diff = array_diff($keys, $this->settings[$Model->alias]['ignore']);
		if (count($diff) == 0 && empty($Model->logableAction)) {
			return false;
		}
		if ($Model->id) {
			$id = $Model->id;
		} elseif ($Model->insertId) {
			$id = $Model->insertId;
		}
		if (isset($this->schema[$this->settings[$Model->alias]['foreignKey']])) {
			$logData['Log'][$this->settings[$Model->alias]['foreignKey']] = $id;
		}
		if (isset($this->schema['description'])) {
			$logData['Log']['description'] = $Model->alias . ' ';
			if (isset($Model->data[$Model->alias][$Model->displayField]) && $Model->displayField != $Model->primaryKey) {
				$logData['Log']['description'] .= '"' . $Model->data[$Model->alias][$Model->displayField] . '" ';
			}

			if ($this->settings[$Model->alias]['description_ids']) {
				$logData['Log']['description'] .= '(' . $id . ') ';
			}

			if ($created) {
				$logData['Log']['description'] .= __('added', true);
			} else {
				$logData['Log']['description'] .= __('updated', true);
			}
		}
		if (isset($this->schema['action'])) {
			if ($created) {
				$logData['Log']['action'] = 'add';
			} else {
				$logData['Log']['action'] = 'edit';
			}

		}
		if (isset($this->schema['change'])) {
			$logData['Log']['change'] = '';
			$dbFields = array_keys($Model->schema());
			$changedFields = array();
			foreach ($Model->data[$Model->alias] as $key => $value) {
				if (isset($Model->data[$Model->alias][$Model->primaryKey]) && !empty($this->old) && isset($this->old[$Model->alias][$key])) {
					$old = $this->old[$Model->alias][$key];
				} else {
					$old = '';
				}
				if ($key != 'modified' && !in_array($key, $this->settings[$Model->alias]['ignore']) && $value != $old && in_array($key, $dbFields)) {
					if ($this->settings[$Model->alias]['change'] == 'full') {
						$changedFields[] = $key . ' (' . $old . ') => (' . $value . ')';
					} elseif ($this->settings[$Model->alias]['change'] == 'serialize') {
						$changedFields[$key] = array(
								'old' => $old,
								'value' => $value);
					} else {
						$changedFields[] = $key;
					}
				}
			}
			$changes = count($changedFields);
			if ($changes == 0) {
				return true;
			}
			if ($this->settings[$Model->alias]['change'] == 'serialize') {
				$logData['Log']['change'] = serialize($changedFields);
			} else {
				$logData['Log']['change'] = implode(', ', $changedFields);
			}
			$logData['Log']['changes'] = $changes;
		}
		$this->__saveLog($Model, $logData);
	}

/**
 * Does the actual saving of the Log model. Also adds the special field if possible.
 *
 * If model field in table, add the Model->alias
 * If action field is NOT in table, remove it from dataset
 * If the userKey field in table, add it to dataset
 * If userData is supplied to model, add it to the title
 *
 * @param Model &$Model using this behavior
 * @param array $logData data to save
 * @param string $title title of log entry, typically model displayField
 * @return null
 */
	private function __saveLog(&$Model, $logData, $title = null) {
		if ($title !== null) {
			$logData['Log']['title'] = $title;
		} elseif ($Model->displayField == $Model->primaryKey) {
			$logData['Log']['title'] = $Model->alias . ' (' . $Model->id . ')';
		} elseif (isset($Model->data[$Model->alias][$Model->displayField])) {
			$logData['Log']['title'] = $Model->data[$Model->alias][$Model->displayField];
		} else {
			$logData['Log']['title'] = $Model->field($Model->displayField);
		}

		if (isset($this->schema[$this->settings[$Model->alias]['classField']])) {
			// by miha nahtigal
			$logData['Log'][$this->settings[$Model->alias]['classField']] = $Model->name;
		}

		if (isset($this->schema[$this->settings[$Model->alias]['foreignKey']]) && !isset($logData['Log'][$this->settings[$Model->alias]['foreignKey']])) {
			if ($Model->id) {
				$logData['Log'][$this->settings[$Model->alias]['foreignKey']] = $Model->id;
			} elseif ($Model->insertId) {
				$logData['Log'][$this->settings[$Model->alias]['foreignKey']] = $Model->insertId;
			}
		}

		if (!isset($this->schema['action'])) {
			unset($logData['Log']['action']);
		} elseif (isset($Model->logableAction) && !empty($Model->logableAction)) {
			$logData['Log']['action'] = implode(',', $Model->logableAction); // . ' ' . $logData['Log']['action'];
			unset($Model->logableAction);
		}

		if (isset($this->schema['version_id']) && isset($Model->version_id)) {
			$logData['Log']['version_id'] = $Model->version_id;
			unset($Model->version_id);
		}

		if (isset($this->schema['ip']) && $this->userIP) {
			$logData['Log']['ip'] = $this->userIP;
		}

		if (isset($this->schema[$this->settings[$Model->alias]['userKey']]) && $this->user) {
			$logData['Log'][$this->settings[$Model->alias]['userKey']] = $this->user[$this->UserModel->alias][$this->UserModel->primaryKey];
		}

		if (isset($this->schema['description'])) {
			if ($this->user['User'] && $this->UserModel) {
				$logData['Log']['description'] .= ' by ' . $this->settings[$Model->alias]['userModel'] . ' "' . $this->user[$this->UserModel->alias][$this->UserModel->displayField] . '"';
				if ($this->settings[$Model->alias]['description_ids']) {
					$logData['Log']['description'] .= ' (' . $this->user[$this->UserModel->alias][$this->UserModel->primaryKey] . ')';
				}

			} else {
				// UserModel is active, but the data hasnt been set. Assume system action.
				$logData['Log']['description'] .= ' by System';
			}
			$logData['Log']['description'] .= '.';
		}
		$this->Log->create($logData);
		$this->Log->save(null, array(
				'validate' => false,
				'callbacks' => false));
	}
}
