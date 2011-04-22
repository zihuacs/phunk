<?php

/**
 * @package phunk.cache
 */
interface phunk_cache_Interface {
	/**
	 * @abstract
	 * @param string $key
	 * @param string $data
	 * @return void
	 */
	public static function set($key, $data);

	/**
	 * @abstract
	 * @param string $key
	 * @return void
	 */
	public static function get($key);
}