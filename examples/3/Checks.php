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
		self::$codePath = realpath(dirname(__FILE__) . '/../../lib');
		self::addCheck(self::getUndefinedFunctionCheck());
		self::addCheck(self::getUndefinedMethodCheck());
		self::addCheck(self::getUndefinedClassCheck());
		self::addCheck(self::getNonCodeCheck());
	}

	/**
	 * @static
	 * @return phunk_check_UndefinedFunction
	 */
	protected static function getUndefinedFunctionCheck() {
		require_once 'phunk/check/UndefinedFunction.php';
		$check = new phunk_check_UndefinedFunction();
		$check->addDependencyPath(self::$codePath);
		$check->addCheckPath(self::$codePath);
		return $check;
	}

	/**
	 * @static
	 * @return phunk_check_UndefinedMethod
	 */
	protected static function getUndefinedMethodCheck() {
		require_once 'phunk/check/UndefinedMethod.php';
		$check = new phunk_check_UndefinedMethod();
		$check->addDependencyPath(self::$codePath);
		$check->addCheckPath(self::$codePath);
		return $check;
	}

	/**
	 * @static
	 * @return phunk_check_UndefinedClass
	 */
	protected static function getUndefinedClassCheck() {
		require_once 'phunk/check/UndefinedClass.php';
		$check = new phunk_check_UndefinedClass();
		$check->addDependencyPath(self::$codePath);
		$check->addCheckPath(self::$codePath);
		return $check;
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