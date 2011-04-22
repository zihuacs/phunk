<?php

require_once 'Phunk.php';
require_once 'phunk/Tokenizer.php';
require_once 'phunk/check/Abstract.php';
require_once 'phunk/parser/Abstract.php';

/**
 * @package phunk.check
 */
class phunk_check_UndefinedFunction extends phunk_check_Abstract {
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
		phunk_parser_Abstract::FUNCTION_USAGE
	);

	/**
	 * @param string $filePath
	 * @param array $data
	 * @return void
	 */
	public function process($filePath, array $data) {
		require_once 'phunk/check/exception/ExceptionCollection.php';
		require_once 'phunk/check/exception/UndefinedFunctionException.php';

		$exceptions = new phunk_check_exception_ExceptionCollection();
		$functions = $this->findUndefined($data[phunk_parser_Abstract::FUNCTION_USAGE]);

		foreach($functions as $function) {
			$exception = new phunk_check_exception_UndefinedFunctionException();
			$exception->setUndefined($function);
			$exception->setFilePath($filePath);
			$exceptions->addException($exception);
		}

		$exceptions->hasCheckFailed();
	}

	/**
	 * @param array $used
	 * @return array
	 */
	protected function findUndefined(array $used) {
		$undefined = array();
		foreach($used as $functionInfo) {
			$function = strtolower($functionInfo['name']);
			if(!isset($this->declared[$function]) && !in_array($function, $this->whitelist)) $undefined[] = $functionInfo;
		}
		return $undefined;
	}

	/**
	 * @return void
	 */
	public function preProcess() {
		if($this->autoLoadStubs) {
			require_once 'phunk/stub/Loader.php';
			$this->declared = array_merge($this->declared, array_fill_keys(phunk_stub_Loader::load(phunk_stub_Loader::TYPE_FUNCTIONS), true));
		}
	}

	/**
	 * @param string $filePath
	 * @return void
	 */
	public function processDependency($filePath) {
		$declarations = Phunk::parse($filePath, phunk_parser_Abstract::FUNCTION_DECLARATION);
		foreach($declarations as $declaration) $this->declared[strtolower($declaration['name'])] = true;
	}

	/**
	 * @param array $whitelist
	 * @return void
	 */
	public function setUndefinedWhitelist(array $whitelist) {
		foreach($whitelist as $item) {
			$this->whitelist[] = strtolower($item);
		}
	}
}