<?php

require_once 'Phunk.php';
require_once 'phunk/check/Abstract.php';

/**
 * @package phunk
 */
abstract class phunk_Suite {
	/**
	 * @var array
	 */
	protected static $checks = array();

	/**
	 * @param phunk_check_Abstract $check
	 * @return void
	 */
	protected static function addIncludePathsAsDependencies(phunk_check_Abstract $check) {
		$includePaths = explode(PATH_SEPARATOR, get_include_path());
		foreach($includePaths as $includePath) {
			if($includePath != '.' && $includePath != '..' && is_dir($includePath) && is_readable($includePath)) {
				$check->addDependencyPath($includePath);
			}
		}
	}

	/**
	 * @abstract
	 * @return void
	 */
	abstract public static function suite();

	/**
	 * @static
	 * @param phunk_check_Abstract $check
	 * @return void
	 */
	protected static function addCheck(phunk_check_Abstract $check) {
		self::$checks[] = $check;
	}

	/**
	 * @static
	 * @return array
	 */
	public static function getChecks() {
		return self::$checks;
	}
}