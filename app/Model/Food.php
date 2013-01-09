<?php
class Food extends AppModel {
	public $name = 'Food';	
	public $displayField = 'LONG_DESC';
	
	public $hasMany = array('NutritionValue');
	
	public $virtualFields = array(
		'name' => 'LONG_DESC',
		'manufacture' => 'MANUFACNAME'
	);
	
	/* Do not allow data to be deleted */
	public function beforeDelete($cascade = true) {
		return false;
    }
	
	/* Do not allow data to be changed */
	public function beforeSave($options = array()) {
		return false;
    }
}