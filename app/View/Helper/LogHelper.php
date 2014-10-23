<?php
class logHelper extends Helper {

	public $helpers = array('Html');
	
	function describe($log, $linkProject = FALSE) {
		$user = $log['user_id'];
		$action = $log['action'];
		$model = $log['model'];
		$model_id = $log['model_id'];

		$user = ClassRegistry::init('User')->find('first',array('conditions'=>array('User.id'=>$log['user_id'])));

		// User
		if( isset($user['User']['username']) ) {
			$strOutput = $this->Html->link($user['User']['username'], array('controller' => 'users', 'action' => 'view', $user['User']['id'])) . ' ';
		} else {
			$strOutput = 'System ';
		}

		// Action
		if( $log['action'] == 'add' ) {
			$strOutput .= 'added ';
		} elseif( $log['action'] == 'edit' ) {
			$strOutput .= 'changed ';
		} elseif( $log['action'] == 'delete' ) {
			$strOutput .= 'deleted ';
		} else {
			$strOutput .= $log['action'] . ' ';
		}

		// Object
		if( $log['action'] == 'edit' ) {
			$strOutput .= $log['model'] . ' ' . $this->Html->link($log['title'], array('controller'=>strtolower(Inflector::pluralize($log['model'])), 'action'=>'view', $log['model_id']));
		} else {
			$strOutput .= 'a ' . $log['model'] . ': ' . $this->Html->link($log['title'], array('controller'=>strtolower(Inflector::pluralize($log['model'])), 'action'=>'view', $log['model_id']));
		}

		// Change
		if( $log['action'] == 'edit' ) {
			$logFormat = array("(", ")", "=>");
			$humanFormat = array("\"", "\"", "to");
			$strOutput .= ': ' . str_replace($logFormat, $humanFormat, $log['change']);
		}
		
		return $strOutput;
	}

}
