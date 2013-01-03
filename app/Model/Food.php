<?php
class Food extends AppModel {
	public $name = 'Food';
	public $useTable = 'food_desc';	
	public $primaryKey = 'NDB_NO';
	public $displayField = 'LONG_DESC';
	
	public $virtualFields = array(
		'id' => 'NDB_NO',
		'name' => 'LONG_DESC',
		'manufacture' => 'MANUFACNAME'
	);
	
	/* Do not allow Food to be deleted */
	public function beforeDelete($cascade = true) {
		return false;
    }
	
	/* Do not allow Food to be changed */
	public function beforeSave($options = array()) {
		return false;
    }
}