<?php
class Weight extends AppModel {
	public $name = 'Weight';
	public $belongsTo = 'User';
	
	public $validate = array(
		'weight'=>array(
			'Not empty'=>array(
				'rule'=>'notEmpty',
				'message'=>'Please enter your weight.'
			),
		    'Is a number'=>array(
		        'rule'=>array('numeric'),
		        'message'=>'Needs to be a number dude'
		    ),
		    'Seems reasonable'=>array(
		        'rule'=>array('range',50,300),
		        'message'=>'Uh... that doesn\'t sound right'
		    )
		)
	);
	
}
?>
