<?php

/**
 * @package phunk.stubs
 */
class phunk_stub_Loader {
	/**
	 * @const TYPE_METHODS
	 */
	const TYPE_METHODS = 'methods';

	/**
	 * @const TYPE_FUNCTIONS string
	 */
	const TYPE_FUNCTIONS = 'functions';

	/**
	 * @const TYPE_CLASSES string
	 */
	const TYPE_CLASSES = 'classes';

	/**
	 * @var array
	 */
	protected static $validTypes = array(self::TYPE_METHODS, self::TYPE_FUNCTIONS, self::TYPE_CLASSES);

	/**
	 * @var string
	 */
	public static $stubsPath;

	/**
	 * @static
	 * @param array $type
	 * @return array
	 */
	public static function load($type) {
		if(!self::$stubsPath) self::$stubsPath = realpath(dirname(__FILE__) . '/../../../data/stubs');
		if(!in_array($type, self::$validTypes))	throw new Exception('Unknown type: '. $type);
		return file(self::$stubsPath . '/' . $type . '.stub', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	}
}
