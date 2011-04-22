<?php

require_once 'Interface.php';

/**
 * @package phunk.cache
 */
class phunk_cache_Disk implements phunk_cache_Interface {
	/**
	 * @var string
	 */
	protected static $basePath;

	/**
	 * @static
	 * @param string $basePath
	 * @return void
	 */
	public static function setBasePath($basePath) {
		self::$basePath = $basePath;
	}

	/**
	 * @static
	 * @return string
	 */
	public static function getBasePath() {
		return self::$basePath;
	}

	/**
	 * @param string $key
	 * @return void
	 */
	public static function get($key) {
		$result = @file_get_contents(self::$basePath . '/' . $key . '.cache');
		if($result === false) $result = null;
		if($result !== null) $result = @unserialize($result);
		return $result;
	}

	/**
	 * @param string $key
	 * @param string $data
	 * @return void
	 */
	public static function set($key, $data) {
		$path = self::$basePath . '/' . $key . '.cache';
		$dir = dirname($path);
		if(!is_dir($dir)) mkdir($dir, 0777, true);
		file_put_contents($path, serialize($data));
	}
}