<?php
/**
 * GoodFixture
 *
 */
class GoodFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'caffeine_level' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => '5,2', 'unsigned' => false),
		'per' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => '5,2', 'unsigned' => false),
		'unit' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '5447f5ee-4be4-4ad8-b6d3-3320b4188753',
			'name' => 'Test good',
			'caffeine_level' => 350,
			'per' => 12.0,
			'unit' => 0,
			'created' => '2014-10-22 18:22:38',
			'modified' => '2014-10-22 18:22:38'
		),
	);

}
