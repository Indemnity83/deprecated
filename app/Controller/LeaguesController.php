<?php
class LeaguesController extends AppController {
	public $name = 'Leagues';
	
	public $paginate = array('limit' => 25);
	
	public function isAuthorized($user) {
		/* Admins can do all */
	    if ($user['role'] == 'admin') {
	        return true;
	    }
	    
	    /* Normal users can't access these */
	    if (in_array($this->action, array('add','delete'))) {
	        return false;
	    }
		
		/* Otherwise, you're good */
	    return true;
	}
	
	public function index() {
		$this->set('leagues', $this->paginate('League'));
	}	
	
	public function view($id = null) {
		$this->League->id = $id;
		
		if (!$this->League->exists()) {
			throw new NotFoundException('Invalid league');
		}
		
		if (!$id) {
			$this->Session->setFlash('Invalid league', 'message_error');
			$this->redirect(array('action' => 'index'));
		}

		$this->League->recursive = 2;
		$this->set('league', $this->League->read());
	}
	
	public function add() {	     
	    if ($this->request->is('post')) {
	        if ($this->League->save($this->request->data)) {
	            $this->Session->setFlash('The league has been created', 'message_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('The league couldn\'t be created. Please correct the errors below and try again.', 'message_warning');
	        }
	    }
	}
	
	public function join() {
	    if ($this->request->is('post')) {	        
	        if ($this->League->addMember($this->request->data)) {
	            $this->Session->setFlash('You\'ve been added to the league', 'message_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('The league voted no. Please correct the errors below and try again.', 'message_warning');
	        }
	    }
	    
	    $this->loadModel('User');
	    $this->set('users', $this->User->find('list'));
	    $this->set('leagues', $this->League->find('list'));
	}
}