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
 * @throws NotFoundException
 * @param string $id
 * @return void
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
 * @throws NotFoundException
 * @param string $id
 * @return void
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
 * @throws NotFoundException
 * @param string $id
 * @return void
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
 */
	public function getUnit() {
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$good = $this->Good->findById($data['Consumption']['good_id']);
			echo $good['Good']['unit_enum'];
			die();
		}

		throw new NotFoundException(__('Invalid good'));
	}

}
