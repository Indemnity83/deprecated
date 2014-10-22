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
				$this->Session->setFlash(__('The consumption has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
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
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Consumption->exists($id)) {
			throw new NotFoundException(__('Invalid consumption'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Consumption->save($this->request->data)) {
				$this->Session->setFlash(__('The consumption has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
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
 * @throws NotFoundException
 * @param string $id
 * @return void
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
		return $this->redirect(array('action' => 'index'));
	}
}
