<?php
class logHelper extends Helper {

    public $helpers = array('Html');
    
    function describe($log, $linkProject = FALSE) {
        $user = $log['user_id'];
        $action = $log['action'];
        $model = $log['model'];
        $model_id = $log['model_id'];

        $user = ClassRegistry::init('User')->find('first',array('conditions'=>array('User.id'=>$log['user_id'])));
        $model = ClassRegistry::init($log['model'])->find('first',array('recursive'=>-1,'conditions'=>array('id'=>$log['model_id'])));

        // User
        if( isset($user['User']['username']) ) {
            $strOutput = $user['User']['username'] . ' ';
        } else {
            $strOutput = 'System ';
        }

        // Action
        if( $log['action'] == 'add' ) {
            $strOutput .= 'added ';
        } elseif( $log['action'] == 'edit' ) {
            $strOutput .= 'updated ';
        } elseif( $log['action'] == 'delete' ) {
            $strOutput .= 'deleted ';
        } else {
            $strOutput .= $log['action'] . ' ';
        }

        // Object
        if( $log['model'] == 'CalendarEvent' ) {
            $strOutput .= 'an event';
        } elseif( $log['model'] == 'Project' ) {
            $strOutput .= 'the project';
        } elseif( $log['model'] == 'Licence' ) {
            $strOutput .= 'the licence';
        } elseif( $log['model'] == 'Equipment' ) {
            $strOutput .= 'a piece of equipment';
        } elseif( $log['model'] == 'EquipmentType' ) {
            $strOutput .= 'the equipment type';
        } elseif( $log['model'] == 'Document' ) {
            $strOutput .= 'a text document';
        } elseif( $log['model'] == 'Discussion' ) {
            $strOutput .= 'a discussion ';
        } elseif( $log['model'] == 'Upload' ) {
            $strOutput .= 'a file';
        } elseif( $log['model'] == 'Todo' ) {
            $strOutput .= 'a to-do list';
        } else {
            $strOutput .= 'a ' . $log['model'];
        }

        // Change
        if( $log['action'] != 'add' ) {
            $logFormat = array("(", ")", "=>");
            $humanFormat = array("\"", "\"", "to");
            $strOutput .= ' ' . str_replace($logFormat, $humanFormat, $log['change']);
        }

        // Name
        $name = '';           
        if( isset($model[$log['model']]['name'])) {
            $name = $model[$log['model']]['name'];
        } elseif( isset($model[$log['model']]['title'])) {
            $name = $model[$log['model']]['title'];
        } elseif( isset($model[$log['model']]['subject'])) {
            $name = $model[$log['model']]['subject'];
        }

        // Link
        if( $log['model'] != 'Project' || $linkProject ) {
            $strOutput .= ': ' . $this->Html->link($name, array('controller'=>Inflector::pluralize($log['model']), 'action'=>'view', $log['model_id']));
        }
        
        return $strOutput;
    }

}
