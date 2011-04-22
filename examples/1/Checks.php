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
		self::addCheck(self::getBlacklistedFunctionCheck());
		self::addCheck(self::getBlacklistedMethodCheck());
		self::addCheck(self::getBlacklistedClassCheck());
	}

	/**
	 * @static
	 * @return phunk_check_BlacklistedFunction
	 */
	protected static function getBlacklistedFunctionCheck() {
		require_once 'phunk/check/BlacklistedFunction.php';
		$check = new phunk_check_BlacklistedFunction();
		$check->setBlacklistedFunctions(array('exec'));
		$check->addCheckPath(self::$codePath);
		return $check;
	}

	/**
	 * @static
	 * @return phunk_check_BlacklistedMethod
	 */
	protected static function getBlacklistedMethodCheck() {
		require_once 'phunk/check/BlacklistedMethod.php';
		$check = new phunk_check_BlacklistedMethod();
		$check->setBlacklistedMethods(array('query'));
		$check->addCheckPath(self::$codePath);
		return $check;
	}

	/**
	 * @static
	 * @return phunk_check_BlacklistedClass
	 */
	protected static function getBlacklistedClassCheck() {
		require_once 'phunk/check/BlacklistedClass.php';
		$check = new phunk_check_BlacklistedClass();
		$check->setBlacklistedClasses(array('mysqli'));
		$check->addCheckPath(self::$codePath);
		return $check;
	}
}