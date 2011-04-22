<?php

require_once 'phunk/check/Abstract.php';
require_once 'phunk/parser/Abstract.php';

/**
 * @package phunk.check
 */
class phunk_check_BlacklistedFunction extends phunk_check_Abstract {
	/**
	 * @var array
	 */
	protected $blacklistedFunctions = array();

	/**
	 * @var array
	 */
	protected $parserRequires = array(
		phunk_parser_Abstract::FUNCTION_USAGE
	);

	/**
	 * @param string $filePath
	 * @param array $data
	 * @return void
	 */
	public function process($filePath, array $data) {
		require_once 'phunk/check/exception/ExceptionCollection.php';
		require_once 'phunk/check/exception/BlacklistedFunctionException.php';

		$exceptions = new phunk_check_exception_ExceptionCollection();
		$blacklisted = array();
		foreach($data[phunk_parser_Abstract::FUNCTION_USAGE] as $function) {
			if(in_array($function['name'], $this->blacklistedFunctions)) $blacklisted[] = $function;
		}

		foreach($blacklisted as $function) {
			$exception = new phunk_check_exception_BlacklistedFunctionException();
			$exception->setFilePath($filePath);
			$exception->setBlacklisted($function);
			$exceptions->addException($exception);
		}

		$exceptions->hasCheckFailed();
	}

	/**
	 * @param array $blacklistedFunctions
	 * @return void
	 */
	public function setBlacklistedFunctions(array $blacklistedFunctions) {
		$this->blacklistedFunctions = $blacklistedFunctions;
	}
}
