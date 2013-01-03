<?php
class WeightsController extends AppController {

	public $name = 'Weights';
	
	public function isAuthorized($user) {
		/* Admins can do all */
	    if ($user['role'] == 'admin') {
	        return true;
	    }

		/* Normal users can't access these */
		if (in_array($this->action, array('edit','delete'))) {
			return false;
		}
		
		/* Otherwise, you're good */
	    return true;
	}
	
	public function weighin() {
		
	    if ($this->request->is('post')) {
	        
	        $this->request->data['Weight']['user_id'] = $this->Auth->user('id');
	        $this->request->data['Weight']['date'] = CakeTime::format('Y-m-d');
			
	        
	        if ($this->Weight->save($this->request->data)) {
	            $this->Session->setFlash('Your weigh-in has been posted', 'message_success');
	            $this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
	        } else {
	            $this->Session->setFlash('Nope, that didn\'t work', 'message_warning');
	        }
	    }
		
		
	}
	
	public function index() {
		$this->set('weights', $this->Weight->find('all'));
	}
	
	public function edit($id = null) {
		$this->Weight->id = $id;
		
		if (!$this->Weight->exists()) {
			throw new NotFoundException('Invalid weight');
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Weight->save($this->request->data)) {
				$this->Session->setFlash('The weight has been saved', 'message_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The weight could not be saved. Please, try again.', 'message_warning');
			}
		} else {
			$this->request->data = $this->Weight->read();
		}
	}
	
	public function delete($id = null) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}
		
		if (!$id) {
			$this->Session->setFlash('Invalid id for weight','message_error');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Weight->delete($id)) {
			$this->Session->setFlash('Weight deleted', 'message_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash('Weight was not deleted', 'message_error');
		$this->redirect(array('action' => 'index'));
	}
	
	
}