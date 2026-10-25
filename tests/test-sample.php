<?php
// @todo
class Sample_Test extends WP_UnitTestCase {

	public function set_up() {
		parent::set_up();
		new Inc2734\WP_Google_Fonts\Bootstrap();
	}

	public function tear_down() {
		parent::tear_down();
	}

	/**
	 * @test
	 */
	public function test() {
		$this->assertTrue( true );
	}
}
