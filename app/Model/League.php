<?php
class League extends AppModel {
    public $name = 'League';
    public $hasMany = array('User');

}