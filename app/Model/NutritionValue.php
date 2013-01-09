<?php
class NutritionValue extends AppModel {
	public $name = 'NutritionValue';	
	public $primaryKey = 'NUTR_NO';
	public $cacheQueries = true;
	
	public $hasOne = array('NutritionDefinition' => array(
            'className'    => 'NutritionDefinition',
            'foreignKey'   => 'NUTR_NO'
        )
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