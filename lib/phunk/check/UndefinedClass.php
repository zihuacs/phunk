<?php

require_once 'Phunk.php';
require_once 'phunk/Tokenizer.php';
require_once 'phunk/check/Abstract.php';
require_once 'phunk/parser/Abstract.php';

/**
 * @package phunk.check
 */
class phunk_check_UndefinedClass extends phunk_check_Abstract {
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
		phunk_parser_Abstract::CLASS_USAGE
	);

	/**
	 * @param string $filePath
	 * @param array $data
	 * @return void
	 */
	public function process($filePath, array $data) {
		require_once 'phunk/check/exception/ExceptionCollection.php';
		require_once 'phunk/check/exception/UndefinedClassException.php';

		$exceptions = new phunk_check_exception_ExceptionCollection();
		$classes = $this->findUndefined($data[phunk_parser_Abstract::CLASS_USAGE]);

		foreach($classes as $class) {
			$exception = new phunk_check_exception_UndefinedClassException();
			$exception->setUndefined($class);
			$exception->setFilePath($filePath);
			$exceptions->addException($exception);
		}

		$exceptions->hasCheckFailed();
	}

	/**
	 * @param array $used
	 * @return array
	 */
	protected function findUndefined($used) {
		$undefined = array();
		foreach($used as $classInfo) {
			$class = strtolower($classInfo['name']);
			if(!isset($this->declared[$class]) && !in_array($class, $this->whitelist)) $undefined[] = $classInfo;
		}
		return $undefined;
	}

	/**
	 * @return void
	 */
	public function preProcess() {
		if($this->autoLoadStubs) {
			require_once 'phunk/stub/Loader.php';
			$this->declared = array_merge($this->declared, array_fill_keys(phunk_stub_Loader::load(phunk_stub_Loader::TYPE_CLASSES), true));
		}
	}

	/**
	 * @param string $filePath
	 * @param array $tokens
	 * @return void
	 */
	public function processDependency($filePath) {
		$declarations = Phunk::parse($filePath, phunk_parser_Abstract::CLASS_DECLARATION);
		foreach($declarations as $declaration) $this->declared[strtolower($declaration['name'])] = true;
	}

	/**
	 * @param array $whitelist
	 * @return void
	 */
	public function setWhitelist(array $whitelist) {
		foreach($whitelist as $item) {
			$this->whitelist[] = strtolower($item);
		}
	}
}
