<?php
/**
 * Command-line code generation utility to automate programmer chores.
 *
 * Bake is CakePHP's code generation script, which can help you kickstart
 * application development by writing fully functional skeleton controllers,
 * models, and views. Going further, Bake can also write Unit Tests for you.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 1.2.0.5012
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppShell', 'Console/Command');
App::uses('Model', 'Model');

/**
 * Command-line code generation utility to automate programmer chores.
 *
 * Bake is CakePHP's code generation script, which can help you kickstart
 * application development by writing fully functional skeleton controllers,
 * models, and views. Going further, Bake can also write Unit Tests for you.
 *
 * @package       Cake.Console.Command
 * @link          http://book.cakephp.org/2.0/en/console-and-shells/code-generation-with-bake.html
 */
class SetupShell extends AppShell {

/**
 * Contains tasks to load and instantiate
 *
 * @var array
 */
	public $tasks = array('DbConfig', 'EmailConfig');

/**
 * The connection being used.
 *
 * @var string
 */
	public $connection = 'default';

/**
 * The models used by this class.
 *
 * @var array
 */
	public $uses = array('Role', 'User');

/**
 * Assign $this->connection to the active task if a connection param is set.
 *
 * @return void
 */
	public function startup() {
		parent::startup();
		Configure::write('debug', 2);
		Configure::write('Cache.disable', 1);

		$task = Inflector::classify($this->command);
		if (isset($this->{$task}) && !in_array($task, array('DbConfig'))) {
			if (isset($this->params['connection'])) {
				$this->{$task}->connection = $this->params['connection'];
			}
		}
		if (isset($this->params['connection'])) {
			$this->connection = $this->params['connection'];
		}
	}

/**
 * Override main() to handle action
 *
 * @return mixed
 */
	public function main() {
		if (!is_dir($this->DbConfig->path)) {
			$path = $this->Project->execute();
			if (!empty($path)) {
				$this->DbConfig->path = $path . 'Config' . DS;
			} else {
				return false;
			}
		}

		if (!config('database')) {
			$this->out(__d('cake_console', 'Your database configuration was not found. Take a moment to create one.'));
			$this->args = null;
			$this->DbConfig->execute();
		}

		$this->out(__d('cake_console', 'Interactive Setup Shell'));
		$this->hr();
		$this->out(__d('cake_console', '[D]atabase Configuration'));
		$this->out(__d('cake_console', '[E]mail Configuration'));
		$this->out(__d('cake_console', '[I]nstall'));
		$this->out(__d('cake_console', '[U]pdate'));
		$this->out(__d('cake_console', '[R]ole'));
		$this->out(__d('cake_console', '[U]ser'));
		$this->out(__d('cake_console', '[Q]uit'));

		$classToBake = strtoupper($this->in(__d('cake_console', 'What would you like to Setup?'), array('D', 'E', 'I', 'U', 'R', 'U', 'P', 'Q')));
		switch ($classToBake) {
			case 'D':
				$this->DbConfig->execute();
				break;
			case 'E':
				$this->EmailConfig->execute();
				break;
			case 'I':
				$this->install();
				break;
			case 'U':
				$this->update();
				break;
			case 'R':
				$this->role();
				break;
			case 'U':
				$this->user();
				break;
			case 'P':
				$this->permissions();
				break;
			case 'Q':
				return $this->_stop();
			default:
				$this->out(__d('cake_console', 'You have made an invalid selection. Please choose a type of class to Bake by entering D, M, V, F, T, or C.'));
		}
		$this->hr();
		$this->main();
	}

/**
 * Install the Application
 *
 * @return void
 */
	public function install() {
		$this->out('Install');
		$this->hr();

		if (!isset($this->params['connection']) && empty($this->connection)) {
			$this->connection = $this->DbConfig->getConfig();
		}

		$confirm = '';
		while (!$confirm) {
			$confirm = $this->in(__d('cake_console', 'Installation will overwrite the entire database, are you sure you want to continue?'), array('y', 'n'), 'n');
		}

		if ($confirm === 'n') return;
		$this->out();

		$this->dispatchShell('schema create -y -q');
		$this->out('<success>Database Schema</success> created');

		$this->dispatchShell('acl create aco root controllers -q');
		$this->dispatchShell('aclExtras.aclExtras aco_sync -q');
		$this->out('<success>Access Control Objects</success> created');

		$this->role(array('title' => 'Admin', 'superRole' => 'y'));

		$this->out();
		$this->hr();
		$this->out('Create Admin User:');
		$this->user(array('role' => 'Admin'));

		$this->out();
		$this->hr();
		$this->out('<success>Installation Successful</success>');
		$this->hr();
		$this->out();

		return $this->_stop();
	}

/**
 * Update the Application
 *
 * @return void
 */
	public function update() {
		$this->out('Update');
		$this->hr();

		$this->dispatchShell('schema update -y -q');
		$this->out('<success>Database Schema Updated</success>');

		$this->dispatchShell('aclExtras.aclExtras aco_sync -q');

		$this->out();
		$this->hr();
		$this->out('<success>Update Successful</success>');
		$this->hr();
		$this->out();

		return $this->_stop();
	}

/**
 * Create a user role
 *
 * @return void
 */
	public function role($params = array()) {

		$title = '';
		$superRole = '';
		extract($params);

		while (!$title) {
			$title = $this->in(__d('cake_console', "Role Title:"), null);

			// validate the role is a simple string
			if (preg_match('/[^a-z0-9_]/i', $title)) {
				$title = '';
				$this->out(__d('cake_console', 'The role title may only contain unaccented latin characters, numbers or underscores'));
			}

			// validate the role is unique
			if ($this->Role->findByTitle($title)) {
				$title = '';
				$this->out(__d('cake_console', 'The role title must be unique'));
			}
		}

		while (!$superRole) {
			$superRole = $this->in(__d('cake_console', 'Role is Super?'), array('y', 'n'), 'n');
		}

		$this->out();
		$this->hr();
		$this->out(__d('cake_console', 'The following user role will be created:'));
		$this->hr();
		$this->out(__d('cake_console', "Title:            %s", $title));
		$this->out(__d('cake_console', "Super Role:       %s", $superRole));
		$this->hr();
		$looksGood = $this->in(__d('cake_console', 'Look okay?'), array('y', 'n'), 'y');

		if ($looksGood === 'n') return $this->_stop();

		// build the data
		$data = array(
			'title' => $title
		);

		$this->out();
		$this->Role->create();
		if ($this->Role->save($data)) {
			$this->dispatchShell('acl create aro root Role.' . $this->Role->id . ' -q');
			if ($superRole === 'y') $this->dispatchShell('acl grant Role.'  . $this->Role->id . ' controllers -q');
			$this->out('<success>New Role</success> \'' . $title . '\' created');
		} else {
			$this->out('<error>New Role</error> failed');
			return $this->_stop();
		}

	}

/**
 * Create a user account
 *
 * @return void
 */
	public function user($params = array()) {

		$username = '';
		$email = '';
		$password = '';
		$role = '';
		extract($params);

		while (!$username) {
			$username = $this->in(__d('cake_console', "Username:"), null);

			// validate the username is a simple string
			if (preg_match('/[^a-z0-9_]/i', $username)) {
				$username = '';
				$this->out(__d('cake_console', 'The username may only contain unaccented latin characters, numbers or underscores'));
			}

			// validate the username is unique
			if ($this->User->findByUsername($username)) {
				$username = '';
				$this->out(__d('cake_console', 'The username must be unique'));
			}
		}

		while (!$email) {
			$email = $this->in(__d('cake_console', "Email:"), null);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$email = '';
				$this->out(__d('cake_console', 'Invalid email address'));
			}
		}

		while (!$password) {
			$password = $this->in(__d('cake_console', "Password:"), null);
		}

		$roles = $this->Role->find('list');
		while (!$role) {
			$role = $this->in(__d('cake_console', "Role:"), $roles);
		}

		$this->out();
		$this->hr();
		$this->out(__d('cake_console', 'The following user will be created:'));
		$this->hr();
		$this->out(__d('cake_console', "Username:         %s", $username));
		$this->out(__d('cake_console', "Email:            %s", $email));
		$this->out(__d('cake_console', "Password:         %s", str_repeat('*', strlen($password))));
		$this->out(__d('cake_console', "Role:             %s", $role));
		$this->hr();
		$looksGood = $this->in(__d('cake_console', 'Look okay?'), array('y', 'n'), 'y');

		if ($looksGood === 'n') return $this->_stop();

		// build the data
		$data = array(
			'username' => $username,
			'email' => $email,
			'password' => $password,
			'password_match' => $password,
			'role_id' => $this->Role->field('id', array('title'=>$role))
		);

		$this->out();
		$this->User->create();
		if ($this->User->save($data)) {
			$this->dispatchShell('acl create aro root User.' . $this->User->id . ' -q');
			$this->out('<success>New User</success> \'' . $username  . '\' created');
		} else {
			$this->out('<error>New User</error> failed');
			return $this->_stop();
		}
	}

/**
 * Gets the option parser instance and configures it.
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->description(
			__d('cake_console',	'The Setup script generates the config files and database schema for the application.' .
			' If run with no command line arguments, Setup guides the user through the configuration process.')
		)->addSubcommand('install', array(
			'help' => __d('cake_console', 'Install the complete Application from scratch.')
		))->addSubcommand('update', array(
			'help' => __d('cake_console', 'Update the application')
		));

		return $parser;
	}

}
