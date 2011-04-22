<?php

/**
 * @package phunk.check.exception
 */
abstract class phunk_check_exception_CheckFailException extends Exception {
	/**
	 * @var string
	 */
	protected $filePath;

	/**
	 * @const int
	 */
	const FAIL_WARNING = 0;

	/**
	 * @const int
	 */
	const FAIL_ERROR = 1;

	/**
	 * @var array
	 */
	protected static $problemTypes = array(
		self::FAIL_ERROR => 'error',
		self::FAIL_WARNING => 'warning'
	);

	/**
	 * @static
	 * @throws InvalidArgumentException
	 * @param string $type
	 * @return string
	 */
	public static function problemTypeToString($type) {
		if(!isset(self::$problemTypes[$type])) throw new InvalidArgumentException();
		return self::$problemTypes[$type];
	}

	/**
	 * @return string
	 */
	public function getFailureDescription() {
		return 'Unknown';
	}

	/**
	 * @return string
	 */
	public function getFailureReason() {
		return $this->getMessage();
	}

	/**
	 * @abstract
	 * @return void
	 */
	abstract public function hasCheckFailed();

	/**
	 * @return int
	 */
	public function getFailLevel() {
		return self::FAIL_ERROR;
	}

	/**
	 * @param string $filePath
	 * @return void
	 */
	public function setFilePath($filePath) {
		$this->filePath = $filePath;
	}

	/**
	 * @return string
	 */
	public function getFilePath() {
		return $this->filePath;
	}
}