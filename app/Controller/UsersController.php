<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * Checks if the current user is authorized for controller actions
 * 
 * @param Model $user the user to check
 * @return bool
 */
	public function isAuthorized($user) {
		// Allow limited access to some methods
		if (in_array($this->action, array('profile', 'settings'))) {
			return true;
		}

		// Check with parent
		return parent::isAuthorized($user);
	}

/**
 * beforeFilter method
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		// Allow full access to register, logout and login
		$this->Auth->allow('add', 'logout', 'login');
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @param string $id record id or slug
 * @return void
 * @throws NotFoundException
 */
	public function view($id = null) {
		$this->User->recursive = 2;
		$options = array('conditions' => array('OR' => array('User.' . $this->User->primaryKey => $id, 'User.username' => $id)));
		if (!$user = $this->User->find('first', $options)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set(compact('user'));
	}

/**
 * profile method
 *
 * @return void
 * @throws NotFoundException
 */
	public function profile() {
		$this->User->recursive = 2;
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $this->Auth->user('id')));
		if (!$user = $this->User->find('first', $options)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set(compact('user'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id record id or slug
 * @return void
 * @throws NotFoundException
 */
	public function edit($id = null) {
		$options = array('conditions' => array('OR' => array('User.' . $this->User->primaryKey => $id, 'User.username' => $id)));
		if (!$user = $this->User->find('first', $options)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$this->request->data = $user;
		}
		$this->set('roles', $this->User->enum('role'));
	}

/**
 * settings method
 *
 * @return void
 * @throws NotFoundException
 */
	public function settings() {
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $this->Auth->user('id')));
		if (!$user = $this->User->find('first', $options)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('User profile has been updated.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'profile'));
			} else {
				$this->Session->setFlash(__('The profile could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$this->request->data = $user;
		}
	}

/**
 * delete method
 *
 * @param string $id record id
 * @return void
 * @throws NotFoundException
 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}

/**
 * login method
 *
 * @return void
 */
	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirect());
			}
			$this->Session->setFlash(__('Invalid username or password, try again'));
		}
	}

/**
 * logout method
 *
 * @return void
 */
	public function logout() {
		$this->Session->destroy();
		return $this->redirect($this->Auth->logout());
	}

}
