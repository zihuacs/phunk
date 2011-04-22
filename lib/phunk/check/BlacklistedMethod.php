<?php

require_once 'phunk/check/Abstract.php';
require_once 'phunk/parser/Abstract.php';

/**
 * @package phunk.check
 */
class phunk_check_BlacklistedMethod extends phunk_check_Abstract {
	/**
	 * @var array
	 */
	protected $blacklistedMethods = array();

	/**
	 * @var array
	 */
	protected $parserRequires = array(
		phunk_parser_Abstract::METHOD_USAGE
	);

	/**
	 * @param string $filePath
	 * @param array $data
	 * @return void
	 */
	public function process($filePath, array $data) {
		require_once 'phunk/check/exception/ExceptionCollection.php';
		require_once 'phunk/check/exception/BlacklistedMethodException.php';

		$exceptions = new phunk_check_exception_ExceptionCollection();
		$blacklisted = array();
		foreach($data[phunk_parser_Abstract::METHOD_USAGE] as $method) {
			if(in_array($method['name'], $this->blacklistedMethods)) $blacklisted[] = $method;
		}

		foreach($blacklisted as $method) {
			$exception = new phunk_check_exception_BlacklistedMethodException();
			$exception->setFilePath($filePath);
			$exception->setBlacklisted($method);
			$exceptions->addException($exception);
		}

		$exceptions->hasCheckFailed();
	}

	/**
	 * @param array $blacklistedMethods
	 * @return void
	 */
	public function setBlacklistedMethods(array $blacklistedMethods) {
		$this->blacklistedMethods = $blacklistedMethods;
	}
}