<?php
App::uses('AppController', 'Controller');
/**
 * Consumptions Controller
 *
 * @property Consumption $Consumption
 * @property PaginatorComponent $Paginator
 */
class ConsumptionsController extends AppController {

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
		if (in_array($this->action, array('add'))) {
			return true;
		}

		// The owner of a record can edit and delete it
		if (in_array($this->action, array('edit', 'delete'))) {
			$consumptionId = $this->request->params['pass'][0];
			if ($this->Consumption->isOwnedBy($consumptionId, $user['id'])) {
				return true;
			}
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
		$this->Consumption->recursive = 0;
		$this->set('consumptions', $this->Paginator->paginate());
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Consumption->create();
			$this->request->data['Consumption']['user_id'] = $this->Auth->user('id');
			if ($this->Consumption->save($this->request->data)) {
				$this->Session->setFlash(__('The consumption has been recorded.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('controller' => 'users', 'action' => 'profile'));
			} else {
				$this->Session->setFlash(__('The consumption could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		}
		$users = $this->Consumption->User->find('list');
		$goods = $this->Consumption->Good->find('list');
		$this->set(compact('users', 'goods'));
	}

/**
 * edit method
 *
 * @param string $id record id
 * @return void
 * @throws NotFoundException
 */
	public function edit($id = null) {
		if (!$this->Consumption->exists($id)) {
			throw new NotFoundException(__('Invalid consumption'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Consumption->save($this->request->data)) {
				$this->Session->setFlash(__('The consumption has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('controller' => 'users', 'action' => 'profile'));
			} else {
				$this->Session->setFlash(__('The consumption could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Consumption.' . $this->Consumption->primaryKey => $id));
			$this->request->data = $this->Consumption->find('first', $options);
		}
		$users = $this->Consumption->User->find('list');
		$goods = $this->Consumption->Good->find('list');
		$this->set(compact('users', 'goods'));
	}

/**
 * delete method
 *
 * @param string $id record id
 * @return void
 * @throws NotFoundException
 */
	public function delete($id = null) {
		$this->Consumption->id = $id;
		if (!$this->Consumption->exists()) {
			throw new NotFoundException(__('Invalid consumption'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Consumption->delete()) {
			$this->Session->setFlash(__('The consumption has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The consumption could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('controller' => 'users', 'action' => 'profile'));
	}
}
