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
         $user = $this->Auth->user();

	    if ($this->request->is('post')) {	
            if ($this->League->validateSecret($this->request->data['League']['league_id'], $this->request->data['League']['league_secret'])) {
                $joinData = array(
                    'League'=>array(
                        'id'=>$this->request->data['League']['league_id']
                    ),
                    'User'=>array(
                        'id'=>$user['id']
                    )
                );
                $this->League->save($joinData);
	            $this->Session->setFlash('You\'ve been added to the league', 'message_success');
	            $this->redirect(array('action' => 'index'));
	        } else {
	            $this->Session->setFlash('The league voted no. Please correct the errors below and try again.', 'message_warning');
	        }
	    }

	    $this->set('leagues', $this->League->find('list'));
	}

    public function matchups() {
        $kyle = $this->League->User->findById(1);
        $keith = $this->League->User->findById(2);
        $claudia = $this->League->User->findById(3);
        $tom = $this->League->User->findById(4);
        $steph = $this->League->User->findById(5);
        $otto = $this->League->User->findById(6);
        $nicole = $this->League->User->findById(7);
        $nick = $this->League->User->findById(8);

        $weeks[] = array(    
            'Week' => array('week'=>1, 'start'=>'2013-01-02', 'end'=>'2013-01-09'),        
            'vs'=>array(array($tom,$nicole),array($claudia,$kyle),array($keith,$otto),array($nick,$steph))            
        );

        $weeks[] = array(    
            'Week' => array('week'=>2, 'start'=>'1/9/2013', 'end'=>'1/15/2013'),        
            'vs'=>array(array($tom,$kyle),array($claudia,$otto),array($keith,$steph),array($nick,$nicole))            
        );

        $weeks[] = array(    
            'Week' => array('week'=>3, 'start'=>'1/15/2013', 'end'=>'1/22/2013'),        
            'vs'=>array(array($tom,$otto),array($claudia,$steph),array($keith,$nicole),array($nick,$kyle))            
        );

        $weeks[] = array(    
            'Week' => array('week'=>4, 'start'=>'1/22/2013', 'end'=>'1/29/2013'),        
            'vs'=>array(array($tom,$steph),array($claudia,$nicole),array($keith,$kyle),array($nick,$otto))            
        );

        $weeks[] = array(    
            'Week' => array('week'=>5, 'start'=>'1/29/2013', 'end'=>'2/5/2013'),        
            'vs'=>array(array($tom,$claudia),array($keith,$nick),array($otto,$steph),array($nicole,$kyle))            
        );

        $weeks[] = array(    
            'Week' => array('week'=>6, 'start'=>'2/5/2013', 'end'=>'2/12/2013'),        
            'vs'=>array(array($tom,$keith),array($claudia,$nick),array($otto,$nicole),array($steph,$kyle))            
        );

        $weeks[] = array(    
            'Week' => array('week'=>7, 'start'=>'2/12/2013', 'end'=>'2/19/2013'),        
            'vs'=>array(array($tom,$nick),array($claudia,$keith),array($otto,$kyle),array($steph,$nicole))            
        );

        foreach ($weeks as &$week) {
            $start = $week['Week']['start'];
            $end = $week['Week']['end'];
            foreach ($week['vs'] as &$vs) {
                $vs[0]['Weight']['pct_loss'] = $this->League->User->Weight->percent_loss($week['Week']['start'], $week['Week']['end'], $vs[0]['User']['id']);
                $vs[1]['Weight']['pct_loss'] = $this->League->User->Weight->percent_loss($week['Week']['start'], $week['Week']['end'], $vs[1]['User']['id']);
            }
        }

        if ($this->request->is('requested')) {
            return $weeks;
        } else {
            $this->set(compact('weeks'));
        }        
    }
}