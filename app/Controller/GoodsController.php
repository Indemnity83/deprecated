<?php
App::uses('AppController', 'Controller');
/**
 * Goods Controller
 *
 * @property Good $Good
 * @property PaginatorComponent $Paginator
 */
class GoodsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * Checks if the current user is authorized for controller actions
 * 
 * @param Model $user the user to check
 * @return bool
 */
	public function isAuthorized($user) {
		// Allow limited access to some methods
		if (in_array($this->action, array('index', 'view', 'getunit'))) {
			return true;
		}

		// Allow trusted users to add, edit & delete
		if (in_array($this->action, array('add', 'edit', 'delete')) && $user['role'] == User::ROLE_TRUSTED) {
			return true;
		}

		// Check with parent
		return parent::isAuthorized($user);
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Good->recursive = 2;
		$this->set('goods', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @param string $id record id or slug
 * @return void
 * @throws NotFoundException
 */
	public function view($id = null) {
		$this->Good->recursive = 2;
		$options = array('conditions' => array('OR' => array('Good.' . $this->Good->primaryKey => $id, 'Good.slug' => $id)));
		if (!$good = $this->Good->find('first', $options)) {
			throw new NotFoundException(__('Invalid good'));
		}
		$this->set(compact('good'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Good->create();
			if ($this->Good->save($this->request->data)) {
				$this->Session->setFlash(__('The good has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The good could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$this->set('units', $this->Good->enum('unit'));
	}

/**
 * edit method
 *
 * @param string $id record id or slug
 * @return void
 * @throws NotFoundException
 */
	public function edit($id = null) {
		$options = array('conditions' => array('OR' => array('Good.' . $this->Good->primaryKey => $id, 'Good.slug' => $id)));
		if (!$good = $this->Good->find('first', $options)) {
			throw new NotFoundException(__('Invalid good'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Good->save($this->request->data)) {
				$this->Session->setFlash(__('The good has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The good could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$this->request->data = $good;
		}
		$this->set('units', $this->Good->enum('unit'));
	}

/**
 * delete method
 *
 * @param string $id record id
 * @return void
 * @throws NotFoundException
 */
	public function delete($id = null) {
		$this->Good->id = $id;
		if (!$this->Good->exists()) {
			throw new NotFoundException(__('Invalid good'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Good->delete()) {
			$this->Session->setFlash(__('The good has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The good could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}

/**
 * AJAX: get units for a good
 *
 * @return void
 * @throws NotFoundException
 */
	public function getunit() {
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$good = $this->Good->findById($data['Consumption']['good_id']);
			echo $good['Good']['unit_enum'];
			die();
		}

		throw new NotFoundException(__('Invalid good'));
	}

}
