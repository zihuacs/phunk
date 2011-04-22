<?php

/**
 * @package phunk.check
 */
abstract class phunk_check_Abstract {
	/**
	 * @var array
	 */
	protected $checkPaths = array();

	/**
	 * @var array
	 */
	protected $excludedCheckPaths = array();

	/**
	 * @var array
	 */
	protected $dependencyPaths = array();

	/**
	 * @var array
	 */
	protected $excludedDependencyPaths = array();

	/**
	 * @var array
	 */
	protected $parserRequires = array();

	/**
	 * @return void
	 */
	public function getParserRequires() {
		return $this->parserRequires;
	}

	/**
	 * @param string $filePath
	 * @return void
	 */
	public function addCheckPath($filePath) {
		$filePath = realpath($filePath);
		$this->verifyPath($filePath);
		$this->checkPaths[$filePath] = true;
	}

	/**
	 * @throws InvalidArgumentException
	 * @param string $filePath
	 * @return void
	 */
	public function addDependencyPath($filePath) {
		$filePath = realpath($filePath);
		$this->verifyPath($filePath);
		$this->dependencyPaths[$filePath] = true;
	}

	/**
	 * @return array
	 */
	public function getDependencyPaths() {
		return $this->dependencyPaths;
	}

	/**
	 * @return array
	 */
	public function getCheckPaths() {
		return $this->checkPaths;
	}

	/**
	 * @param string $filePath
	 * @return void
	 */
	public function excludeCheckPath($filePath) {
		$filePath = realpath($filePath);
		$this->verifyPath($filePath);
		$this->excludedCheckPaths[$filePath] = true;
	}

	/**
	 * @param string $filePath
	 * @return void
	 */
	public function excludeDependencyPath($filePath) {
		$filePath = realpath($filePath);
		$this->verifyPath($filePath);
		$this->excludedDependencyPaths[$filePath] = true;
	}

	/**
	 * @param string $filePath
	 * @return void
	 */
	public function isDependency($filePath) {
		if($this->isPathInList($filePath, $this->excludedDependencyPaths)) return false;
		return $this->isPathInList($filePath, $this->dependencyPaths);
	}

	/**
	 * @param string $filePath
	 * @return bool
	 */
	public function isCheckable($filePath) {
		if($this->isPathInList($filePath, $this->excludedCheckPaths)) return false;
		return $this->isPathInList($filePath, $this->checkPaths);
	}

	/**
	 * @param string $filePath
	 * @param array $paths
	 * @return bool
	 */
	protected function isPathInList($filePath, array $paths) {
		foreach($paths as $path => $exists) {
			if(strpos($filePath, $path) === 0) return true;
		}
		return false;
	}

	/**
	 * @throws InvalidArgumentException
	 * @param string $filePath
	 * @return void
	 */
	protected function verifyPath($filePath) {
		if(!$filePath || !is_readable($filePath)) throw new InvalidArgumentException();
	}

	/**
	 * @static
	 * @throws phunk_check_exception_CheckFailException
	 * @return int
	 */
	public function preProcess() { }

	/**
	 * @param string $filePath
	 * @return void
	 */
	public function processDependency($filePath) { }

	/**
	 * @abstract
	 * @param string $filePath
	 * @param array $data
	 * @throws phunk_check_exception_CheckFailException
	 * @return void
	 */
	abstract public function process($filePath, array $tokens);

	/**
	 * @static
	 * @throws phunk_check_exception_CheckFailException
	 * @return void
	 */
	public function postProcess() { }
}