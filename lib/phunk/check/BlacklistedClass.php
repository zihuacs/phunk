<?php

require_once 'phunk/check/Abstract.php';
require_once 'phunk/parser/Abstract.php';

/**
 * @package phunk.check
 */
class phunk_check_BlacklistedClass extends phunk_check_Abstract {
	/**
	 * @var array
	 */
	protected $blacklistedClasses = array();

	/**
	 * @var array
	 */
	protected $parserRequires = array(
		phunk_parser_Abstract::CLASS_USAGE
	);

	/**
	 * @param string $filePath
	 * @param array $data
	 * @return void
	 */
	public function process($filePath, array $data) {
		require_once 'phunk/check/exception/ExceptionCollection.php';
		require_once 'phunk/check/exception/BlacklistedClassException.php';

		$exceptions = new phunk_check_exception_ExceptionCollection();
		$blacklisted = array();
		foreach($data[phunk_parser_Abstract::CLASS_USAGE] as $class) {
			if(in_array($class['name'], $this->blacklistedClasses)) $blacklisted[] = $class;
		}

		foreach($blacklisted as $class) {
			$exception = new phunk_check_exception_BlacklistedClassException();
			$exception->setFilePath($filePath);
			$exception->setBlacklisted($class);
			$exceptions->addException($exception);
		}

		$exceptions->hasCheckFailed();
	}

	/**
	 * @param array $blacklistedClasses
	 * @return void
	 */
	public function setBlacklistedClasses(array $blacklistedClasses) {
		$this->blacklistedClasses = $blacklistedClasses;
	}
}
