<?php
class FoodsController extends AppController {
	public $name = 'Foods';
	
	public $paginate = array(
        'limit' => 25,
        'order' => array(
            'Food_desc.shrt_desc' => 'asc'
        )
    );
	
	public $hasMany = array('Nutrition' => array(
            'className'    => 'Nutrition',
            'foreignKey'   => 'NUTR_NO'
        )
    );
	
	public function isAuthorized($user) {
		/* Admins can do all */
	    if ($user['role'] == 'admin') {
	        return true;
	    }
		
		/* Otherwise, you're good */
	    return true;
	}
	
	public function index() {
		$this->set('foods', $this->paginate('Food'));
	}	
	
	public function view($id = null) {
		$this->Food->id = $id;
		
		if (!$this->Food->exists()) {
			throw new NotFoundException('Invalid food');
		}
		
		if (!$id) {
			$this->Session->setFlash('Invalid food', 'message_error');
			$this->redirect(array('action' => 'index'));
		}
				
		$this->set('food', $this->Food->read());
	}
}