<?php

/**
 * @package phunk
 */
class phunk_DataStore {
	/**
	 * @var phunk_DataStore
	 */
	protected static $instance;

	/**
	 * @return string
	 */
	protected $filePath;

	/**
	 * @var array
	 */
	protected $data = array();

	/**
	 * @return void
	 */
	private final function __construct() {
		$this->filePath = dirname(__FILE__) . '/../../data/phunk.data';
		$data = @unserialize(file_get_contents($this->filePath));
		if(!is_array($data)) $data = array();
		$this->data = $data;
	}

	/**
	 * @static
	 * @return phunk_DataStore
	 */
	public static function instance() {
		if(!isset(self::$instance)) self::$instance = new self();
		return self::$instance;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function setValue($key, $value) {
		$this->data[$key] = $value;
	}

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	public function getValue($key) {
		if(!isset($this->data[$key])) return null;
		return $this->data[$key];
	}

	/**
	 * @return void
	 */
	public function __destruct() {
		file_put_contents($this->filePath, serialize($this->data));
	}
}