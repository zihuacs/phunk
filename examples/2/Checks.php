<?php

require_once 'lib/phunk/Suite.php';

/**
 * @category Testing
 */
class Checks extends phunk_Suite {
	/**
	 * @var string
	 */
	private static $codePath;

	/**
	 * @static
	 * @return void
	 */
	public static function suite() {
		self::$codePath = dirname(__FILE__) . '/code/';
		self::addCheck(self::getNonCodeCheck());
	}

	/**
	 * @static
	 * @return phunk_check_NonCode
	 */
	protected static function getNonCodeCheck() {
		require_once 'phunk/check/NonCode.php';
		$check = new phunk_check_NonCode();
		$check->addCheckPath(self::$codePath);
		return $check;
	}
}