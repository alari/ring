<?php
/**
 * Static test suite.
 */
class R_Tests_Suite extends PHPUnit_Framework_TestSuite {

	/**
	 * Constructs the test suite handler.
	 */
	public function __construct()
	{
		$this->setName( 'R_Tests_Suite' );

	}

	/**
	 * Creates the suite.
	 */
	public static function suite()
	{
		return new self( );
	}
}