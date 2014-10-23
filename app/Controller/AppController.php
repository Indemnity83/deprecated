<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array(
		'Session',
		'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
		'Form' => array('className' => 'BoostCake.BoostCakeForm'),
		'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
		'App',
		'Log'
	);

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Acl',
		'Auth',
		'Session',
		'DebugKit.Toolbar'
	);

/**
 * beforeFilter method
 *
 * @return void
 */
	public function beforeFilter() {

		parent::beforeFilter();

		# configure the logable behavior
		if (sizeof($this->uses) && $this->{$this->modelClass}->Behaviors->attached('Logable')) {
			$this->{$this->modelClass}->setUserData($this->activeUser);
		}

		# configure AuthComponent
		$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'profile');
		$this->Auth->logoutRedirect = array('controller' => 'pages', 'action' => 'display', 'home');
		$this->Auth->authenticate = array('Form' => array('passwordHasher' => 'Blowfish'));
		$this->Auth->flash = array('element' => 'default', 'key' => 'auth', 'params' => array('class' => 'alert alert-info'));
		$this->Auth->authorize = array('Actions' => array('actionPath' => 'controllers'));
		$this->Auth->authError = 'Please login first';
		$this->Auth->allow('display');

		#TODO: Setup real permissions, for now just open it all up if user is logged in
		if ($this->Auth->loggedIn()) {
			$this->Auth->allow();
		}

	}

}
