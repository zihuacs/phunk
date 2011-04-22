<?php

require_once 'phunk/check/exception/CheckFailException.php';

/**
 * @package phunk.check.exception
 */
class phunk_check_exception_NonCodeException extends phunk_check_exception_CheckFailException {
	/**
	 * @var array
	 */
	protected $nonCode;

	/**
	 * @param array $nonCode
	 * @return void
	 */
	public function setNonCode(array $nonCode) {
		$this->nonCode = $nonCode;
	}

	/**
	 * @return string
	 */
	public function getFailureDescription() {
		return 'Non-code portion';
	}

	/**
	 * @return string
	 */
	public function getFailureReason() {
		return 'Text on line ' . $this->nonCode['line'] . ' is not allowed';
	}

	/**
	 * @return int
	 */
	public function getFailLevel() {
		return self::FAIL_ERROR;
	}

	/**
	 * @throws phunk_check_exception_NonCodeException
	 * @return void
	 */
	public function hasCheckFailed() {
		if(isset($this->nonCode)) throw $this;
	}
}