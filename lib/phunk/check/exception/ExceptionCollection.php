<?php

/**
 * @throws phunk_check_exception_ExceptionCollection
 * @package phunk.check.exception
 */
class phunk_check_exception_ExceptionCollection extends phunk_check_exception_CheckFailException {
	/**
	 * @var array
	 */
	protected $exceptions = array();

	/**
	 * @param Exception $e
	 * @return void
	 */
	public function addException(phunk_check_exception_CheckFailException $e) {
		$this->exceptions[] = $e;
	}

	/**
	 * @throws phunk_check_exception_ExceptionCollection
	 * @return void
	 */
	public function hasCheckFailed() {
		/** @var $exception phunk_check_exception_CheckFailException */
		foreach($this->exceptions as $exception) {
			try {
				$exception->hasCheckFailed();
			}
			catch(phunk_check_exception_CheckFailException $e) {
				throw $this;
			}
		}
	}

	/**
	 * @return int
	 */
	public function getFailLevel() {
		$highest = phunk_check_exception_CheckFailException::FAIL_WARNING;

		/** @var $exception phunk_check_exception_CheckFailException */
		foreach($this->exceptions as $exception) {
			$highest = max($highest, $exception->getFailLevel());
		}

		return $highest;
	}

	/**
	 * @return array
	 */
	public function getExceptions() {
		return $this->exceptions;
	}
}