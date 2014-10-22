<?php
App::uses('User', 'Model');

/**
 * User Test Case
 *
 */
class UserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user',
		'app.log',
		'app.role'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->User);

		parent::tearDown();
	}

	public function testBeforeSave() {
		$this->markTestIncomplete('testBeforeSave not implemented.');
	}

	public function testEqualtofield() {
		$this->markTestIncomplete('testEqualtofield not implemented.');
	}

	public function testParentNode() {
		$this->markTestIncomplete('testParentNode not implemented.');
	}

}
