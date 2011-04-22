<?php

require_once 'phunk/check/Abstract.php';
require_once 'phunk/parser/Abstract.php';

/**
 * @package phunk.check
 */
class phunk_check_NonCode extends phunk_check_Abstract {
	/**
	 * @var array
	 */
	protected $declared = array();

	/**
	 * @var array
	 */
	protected $whitelist = array();

	/**
	 * @var bool
	 */
	protected $autoLoadStubs = true;

	/**
	 * @var array
	 */
	protected $parserRequires = array(
		phunk_parser_Abstract::TEXT_OUTPUT
	);

	/**
	 * @param string $filePath
	 * @param array $data
	 * @return void
	 */
	public function process($filePath, array $data) {
		require_once 'phunk/check/exception/ExceptionCollection.php';
		require_once 'phunk/check/exception/NonCodeException.php';

		$exceptions = new phunk_check_exception_ExceptionCollection();

		foreach($data[phunk_parser_Abstract::TEXT_OUTPUT] as $entry) {
			$exception = new phunk_check_exception_NonCodeException();
			$exception->setFilePath($filePath);
			$exception->setNonCode($entry);
			$exceptions->addException($exception);
		}

		$exceptions->hasCheckFailed();
	}
}
