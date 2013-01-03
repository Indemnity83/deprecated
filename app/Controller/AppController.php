<?php

class AppController extends Controller {
    public $components = array(
        'Session',
        'Auth'=>array(
            'loginRedirect'=>array('controller'=>'pages', 'action'=>'index'),
            'logoutRedirect'=>array('controller'=>'users', 'action'=>'login'),
            'flash'=>array('element'=>'message_warning', 'key'=>'auth', 'params'=>array()),
            'authError'=>"Whoa there, you don't have permission for that!",
            'authorize'=>array('Controller')
        )
    );
    
    public function isAuthorized($user) {
        return true;
    }
    
    public function beforeFilter() {
        $this->set('siteName', 'WEIGHTWIRE');
        $this->set('logged_in', $this->Auth->loggedIn());
        $this->set('current_user', $this->Auth->user());
    }
    
}
