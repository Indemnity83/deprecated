<?php
App::uses('Consumption', 'Model');

/**
 * Consumption Test Case
 *
 */
class ConsumptionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.consumption',
		'app.user',
		'app.role',
		'app.log',
		'app.good'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Consumption = ClassRegistry::init('Consumption');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Consumption);

		parent::tearDown();
	}

	public function testBeforeSave() {
		$this->markTestIncomplete('testBeforeSave not implemented.');
	}

	public function testDateFormatBeforeSave() {
		$this->markTestIncomplete('testDateFormatBeforeSave not implemented.');
	}

	public function testAfterFind() {
		$this->markTestIncomplete('testAfterFind not implemented.');
	}

	public function testDateFormatAfterFind() {
		$this->markTestIncomplete('testDateFormatAfterFind not implemented.');
	}	

}
