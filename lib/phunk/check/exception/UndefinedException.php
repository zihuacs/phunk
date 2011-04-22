<?php

require_once 'phunk/check/exception/CheckFailException.php';

/**
 * @package phunk.check.exception
 */
class phunk_check_exception_UndefinedException extends phunk_check_exception_CheckFailException {
	/**
	 * @var array
	 */
	protected $undefined;

	/**
	 * @var string
	 */
	protected $flavour = '';

	/**
	 * @return string
	 */
	public function getFailureDescription() {
		return 'Undefined ' . $this->flavour;
	}

	/**
	 * @param array $undefined
	 * @return void
	 */
	public function setUndefined(array $undefined) {
		$this->undefined = $undefined;
	}

	/**
	 * @return string
	 */
	public function getFailureReason() {
		return '"' . $this->undefined['name'] . '" on line ' . $this->undefined['line'] . ' is an undefined ' . $this->flavour;
	}

	/**
	 * @throws phunk_check_exception_UndefinedException
	 * @return void
	 */
	public function hasCheckFailed() {
		if(isset($this->undefined)) throw $this;
	}
}