<?php
class UsersController extends AppController {

	public $name = 'Users';
	
	public function beforeFilter() {
	    parent::beforeFilter();
	    $this->Auth->allow('login', 'register');
	}
	
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
	
	public function login() {
	    $this->layout = 'lite';
	    
	    if ($this->request->is('post')) {
	        if ($this->Auth->login()) {
	            $this->redirect($this->Auth->redirect());   
	        } else {
	            $this->Session->setFlash('No, you fool! That\'s not your login!', 'message_warning');
	        }
	    }
	}
	
	public function logout() {
	    $this->redirect($this->Auth->logout());
	}
	
    public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->User->find('all'));
	}

	public function view($id = null) {
		$this->User->id = $id;
		
		if (!$this->User->exists()) {
			throw new NotFoundException('Invalid user');
		}
		
		if (!$id) {
			$this->Session->setFlash('Invalid user');
			$this->redirect(array('action' => 'index'));
		}
		$this->set('user', $this->User->read());
	}
	
	public function register() {
	    $this->layout = 'lite';
	    
	    if ($this->request->is('post')) {
	        if ($this->User->save($this->request->data)) {
	            $this->Session->setFlash('The user has been saved');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('Your account couldn\'t be created. Please correct the errors below and try again.', 'message_warning');
	        }
	    }
	}

	public function edit($id = null) {
		$this->User->id = $id;
		
		if (!$this->User->exists()) {
			throw new NotFoundException('Invalid user');
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('The user has been saved');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The user could not be saved. Please, try again.', 'message_warning');
			}
		} else {
			$this->request->data = $this->User->read();
		}
	}
	
	public function profile() {
	    $this->User->id = $this->Auth->user('id');
	
	    if (!$this->User->exists()) {
	        throw new NotFoundException('Invalid user');
	    }
	
	    if ($this->request->is('post') || $this->request->is('put')) {
	        if ($this->User->save($this->request->data)) {
	            $this->Session->setFlash('Your changes have been saved','message_success');
	        } else {
	            $this->Session->setFlash('Your changes could not be saved. Please, try again.', 'message_warning');
	        }
	    } else {
	        $this->request->data = $this->User->read();
	    }
	}

	public function delete($id = null) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}
		
		if (!$id) {
			$this->Session->setFlash('Invalid id for user');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash('User deleted');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash('User was not deleted');
		$this->redirect(array('action' => 'index'));
	}
}
?>
