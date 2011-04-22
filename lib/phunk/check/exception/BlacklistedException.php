<?php

require_once 'phunk/check/exception/CheckFailException.php';

/**
 * @package phunk.check.exception
 */
class phunk_check_exception_BlacklistedException extends phunk_check_exception_CheckFailException {
	/**
	 * @var array
	 */
	protected $blacklisted;

	/**
	 * @var string
	 */
	protected $flavour = '';

	/**
	 * @param array $blacklisted
	 * @return void
	 */
	public function setBlacklisted(array $blacklisted) {
		$this->blacklisted = $blacklisted;
	}

	/**
	 * @return string
	 */
	public function getFailureDescription() {
		return 'Blacklisted ' . $this->flavour;
	}

	/**
	 * @return string
	 */
	public function getFailureReason() {
		return '"' . $this->blacklisted['name'] . '" on line ' . $this->blacklisted['line'] . ' is a blacklisted ' . $this->flavour;
	}

	/**
	 * @return int
	 */
	public function getFailLevel() {
		return self::FAIL_WARNING;
	}

	/**
	 * @throws phunk_check_exception_BlacklistedException
	 * @return void
	 */
	public function hasCheckFailed() {
		if(isset($this->blacklisted)) throw $this;
	}
}