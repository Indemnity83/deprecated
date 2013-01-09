<?php
class NutritionDefinition extends AppModel {
	public $name = 'NutritionDefinition';
	public $primaryKey = 'NUTR_NO';
	public $displayField = 'displayname';
	public $order = "SR_ORDER ASC";
	public $cacheQueries = true;
	
	public $virtualFields = array(
		'displayname' => 'CONCAT( `NUTRDESC` , " (", `UNITS` , ")" )'
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