<?php
/**
 * The EmailConfig Task handles creating and updating the email.php
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
 * @since         CakePHP(tm) v 1.2
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppShell', 'Console/Command');

/**
 * Task class for creating and updating the email configuration file.
 *
 * @package       Cake.Console.Command.Task
 */
class EmailConfigTask extends AppShell {

/**
 * path to CONFIG directory
 *
 * @var string
 */
	public $path = null;

/**
 * Default configuration settings to use
 *
 * @var array
 */
	protected $_defaultConfig = array(
		'transport' => 'Smtp',
		'host' => 'localhost',
		'port' => 25,
		'timeout' => 30,
		'login' => 'user',
		'password' => 'secret',
		'client' => null,
		'log' => false,
	);

/**
 * String name of the email config class name.
 * Used for testing.
 *
 * @var string
 */
	public $emailClassName = 'EmailConfig';

/**
 * initialization callback
 *
 * @return void
 */
	public function initialize() {
		$this->path = APP . 'Config' . DS;
	}

/**
 * Execution method always used for tasks
 *
 * @return void
 */
	public function execute() {
		if (empty($this->args)) {
			$this->_interactive();
			return $this->_stop();
		}
	}

/**
 * Interactive interface
 *
 * @return void
 */
	protected function _interactive() {
		$this->hr();
		$this->out(__d('cake_console', 'Email Configuration:'));
		$this->hr();
		$done = false;
		$emailConfigs = array();

		while (!$done) {
			$name = '';

			while (!$name) {
				$name = $this->in(__d('cake_console', "Name:"), null, 'default');
				if (preg_match('/[^a-z0-9_]/i', $name)) {
					$name = '';
					$this->out(__d('cake_console', 'The name may only contain unaccented latin characters, numbers or underscores'));
				} elseif (preg_match('/^[^a-z_]/i', $name)) {
					$name = '';
					$this->out(__d('cake_console', 'The name must start with an unaccented latin character or an underscore'));
				}
			}

			$transport = $this->in(__d('cake_console', 'Transport:'), array('Mail', 'Smtp', 'Debug'), 'Smtp');

			$fromAddress = '';
			while (!$fromAddress) {
				$fromAddress = $this->in(__d('cake_console', 'From Address:'), null, 'site@localhost');
			}

			$fromName = $this->in(__d('cake_console', 'From Name:'), null, 'My Site');

			$host = '';
			while (!$host) {
				$host = $this->in(__d('cake_console', 'Host:'), null, 'localhost');
			}

			$port = '';
			while (!$port) {
				$port = $this->in(__d('cake_console', 'Port?'), null, '25');
			}

			$timeout = '';
			while (!$port) {
				$port = $this->in(__d('cake_console', 'Timeout:'), null, '30');
			}

			$login = '';
			while (!$login) {
				$login = $this->in(__d('cake_console', 'User:'), null, 'root');
			}
			$password = '';
			$blankPassword = false;

			while (!$password && !$blankPassword) {
				$password = $this->in(__d('cake_console', 'Password:'));

				if (!$password) {
					$blank = $this->in(__d('cake_console', 'The password you supplied was empty. Use an empty password?'), array('y', 'n'), 'n');
					if ($blank === 'y') {
						$blankPassword = true;
					}
				}
			}

			$config = compact('name', 'transport', 'fromAddress', 'fromName', 'host', 'login', 'password', 'port');

			while (!$this->_verify($config)) {
				$this->_interactive();
			}

			$emailConfigs[] = $config;
			$doneYet = $this->in(__d('cake_console', 'Do you wish to add another email configuration?'), null, 'n');

			if (strtolower($doneYet === 'n')) {
				$done = true;
			}
		}

		$this->bake($emailConfigs);
		config('email');
		return true;
	}

/**
 * Output verification message and bake if it looks good
 *
 * @param array $config The config data.
 * @return bool True if user says it looks good, false otherwise
 */
	protected function _verify($config) {
		$config += $this->_defaultConfig;
		extract($config);
		$this->out();
		$this->hr();
		$this->out(__d('cake_console', 'The following email configuration will be created:'));
		$this->hr();
		$this->out(__d('cake_console', "Name:         %s", $name));
		$this->out(__d('cake_console', "Transport:    %s", $transport));
		$this->out(__d('cake_console', "Host:         %s", $host));

		if ($port) {
			$this->out(__d('cake_console', "Port:         %s", $port));
		}

		$this->out(__d('cake_console', "User:         %s", $login));
		$this->out(__d('cake_console', "Pass:         %s", str_repeat('*', strlen($password))));
		$this->out(__d('cake_console', "From Address: %s", $fromAddress));
		$this->out(__d('cake_console', "From Name:    %s", $fromName));

		$this->hr();
		$looksGood = $this->in(__d('cake_console', 'Look okay?'), array('y', 'n'), 'y');

		if (strtolower($looksGood) === 'y') {
			return $config;
		}
		return false;
	}

/**
 * Gets the option parser instance and configures it.
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->description(
			__d('cake_console', 'Bake new email configuration settings.')
		);

		return $parser;
	}

}
