<?php
class League extends AppModel {
    public $name = 'League';

    const WEEKLY = 1;
    const DAILY = 0;

    public $hasAndBelongsToMany = array('Users');

    public function countMembers($id) {
        $this->League->id = $id;
         
        if (!$this->League->exists()) {
            throw new NotFoundException('Invalid league');
        }
         
        $this->League->find('count', array('id' => $id));
    }

    public function addMember($data = array()) {

        if (!$this->League->User->exists($data['user_id'])) {
            throw new NotFoundException('Invalid user');
        }
         
        if (!$this->League->exists($data['league_id'])) {
            throw new NotFoundException('Invalid league');
        }
         
        $match = $this->League->find('count', array('league_secret' => $data['league_secret']));
        if($match == 1) {
            die('Success');
        }
         
        die('Failure');
    }




}