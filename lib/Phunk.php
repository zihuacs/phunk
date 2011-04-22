<?php

require_once 'phunk/check/exception/CheckFailException.php';
require_once 'phunk/Tokenizer.php';
require_once 'phunk/Logger.php';
require_once 'phunk/parser/Runner.php';

/**
 *
 * @category Testing
 */
class Phunk {
	/**
	 * @var array
	 */
	protected $problems = array();

	/**
	 * @var array
	 */
	protected $checks = array();

	/**
	 * @var string
	 */
	protected static $cachePathPrefix;

	/**
	 * @const float
	 */
	const VERSION = '1.00';

	/**
	 * @param array $checks
	 * @param array $checkFiles
	 * @param array $dependencyFiles
	 * @return void
	 */
	public function process(array $checks, array $checkFiles, array $dependencyFiles) {
		$this->checks = $checks;
		$store = phunk_DataStore::instance();

		// Begin.
		phunk_Logger::write('phunk v' . self::VERSION . PHP_EOL);

		// Check the disk cache availability
		self::$cachePathPrefix = '/parsers/';
		$lastVersion = $store->getValue('last_parser_version');
		if(!is_dir(phunk_cache_Disk::getBasePath() . self::$cachePathPrefix) || phunk_parser_Abstract::VERSION != $lastVersion) {
			$store->setValue('last_parser_version', phunk_parser_Abstract::VERSION);
			phunk_Logger::write('WARNING: disk cache does not exist - first run may take some time.' . PHP_EOL);
		}

		/** @var $check phunk_check_Abstract */
		foreach($checks as $check) {
			$this->doPreProcess($check);
		}

		// Dependencies
		foreach($dependencyFiles as $filePath => $exists) {
			$this->processDependencyFile($filePath);
		}

		// Actual checks
		foreach($checkFiles as $filePath => $exists) {
			$this->processCheckFile($filePath);
		}

		/** @var $check phunk_check_Abstract */
		foreach($checks as $check) {
			$this->doPostProcess($check);
		}

		phunk_Logger::write(PHP_EOL);

		// Check results
		$hasErrors = isset($this->problems[phunk_check_exception_CheckFailException::FAIL_ERROR]) && count($this->problems[phunk_check_exception_CheckFailException::FAIL_ERROR]) > 0;
		$hasWarnings = isset($this->problems[phunk_check_exception_CheckFailException::FAIL_WARNING]) && count($this->problems[phunk_check_exception_CheckFailException::FAIL_WARNING]) > 0;

		if($hasErrors) $this->displayFailures();
		if($hasWarnings) $this->displayWarnings();

		if($hasErrors) phunk_Logger::write('FAILED!');
		else if($hasWarnings) phunk_Logger::write('PASSED! - but address warnings');
		else phunk_Logger::write('PASSED');

		return $hasErrors;
	}

	/**
	 * @return void
	 */
	protected function displayFailures() {
		$this->displayProblems(phunk_check_exception_CheckFailException::FAIL_ERROR);
	}

	/**
	 * @return void
	 */
	protected function displayWarnings() {
		$this->displayProblems(phunk_check_exception_CheckFailException::FAIL_WARNING);
	}

	/**
	 * @param string $type
	 * @return void
	 */
	protected function displayProblems($type) {
		$problemType = phunk_check_exception_CheckFailException::problemTypeToString($type);
		$total = count($this->problems[$type]);
		phunk_Logger::write('There ' . ($total > 1 ? 'were' : 'was') . ' ' . $total . ' ' . $problemType . ($total > 1 ? 's' : '') . ':');
		phunk_Logger::write();
		$problems = array();

		/** @var $problem phunk_check_exception_CheckFailException */
		foreach($this->problems[$type] as $problem) {
			$key = $problem->getFilePath();
			if(!isset($problems[$key])) $problems[$key] = array();
			$problems[$key][] = $problem;
		}

		$i = 0;
		foreach($problems as $filePath => $problemList) {
			phunk_Logger::write('In ' . $filePath . ':');
			foreach($problemList as $problem) phunk_Logger::write(++$i . ') ' . $problem->getFailureReason());
			phunk_Logger::write();
		}
	}

	/**
	 * @param phunk_check_Abstract $check
	 * @return void
	 */
	protected function doPreProcess(phunk_check_Abstract $check) {
		try {
			$check->preProcess();
		}
		catch(phunk_check_exception_CheckFailException $e) {
			$this->handleFailure($e);
		}
	}

	/**
	 * @param string $filePath
	 * @return void
	 */
	public function processDependencyFile($filePath) {
		static $tally = 0;
		if(++$tally % 20 == 0) phunk_Logger::displayShort('-');
		phunk_Logger::write('Processing dependency ' . $filePath, phunk_Logger::LEVEL_VERBOSE);

		/** @var $check phunk_check_Abstract */
		foreach($this->checks as $check) {
			if($check->isDependency($filePath)) {
				try {
					$check->processDependency($filePath);
				}
				catch(phunk_check_exception_CheckFailException $e) {
					$this->handleFailure($e);
				}
			}
		}
	}

	/**
	 * @param string $filePath
	 * @return void
	 */
	public function processCheckFile($filePath) {
		phunk_Logger::write('Checking ' . $filePath, phunk_Logger::LEVEL_VERBOSE);
		$failure = false;
		$warning = false;

		/** @var $check phunk_check_Abstract */
		foreach($this->checks as $check) {
			if($check->isCheckable($filePath)) {
				try {
					$requires = $check->getParserRequires();
					$data = array();
					foreach($requires as $require) $data[$require] = self::parse($filePath, $require);
					$check->process($filePath, $data);
				}
				catch(phunk_check_exception_CheckFailException $e) {
					$this->handleFailure($e);
					$level = $e->getFailLevel();
					if($level === phunk_check_exception_CheckFailException::FAIL_ERROR) $failure = true;
					else if($level === phunk_check_exception_CheckFailException::FAIL_WARNING) $warning = true;
				}
			}
		}

		if($failure) phunk_Logger::displayShort(phunk_Logger::SHORT_CHAR_ERROR);
		else if($warning) phunk_Logger::displayShort(phunk_Logger::SHORT_CHAR_WARNING);
		else phunk_Logger::displayShort(phunk_Logger::SHORT_CHAR_SUCCESS);
	}

	/**
	 * @static
	 * @param string $filePath
	 * @param string $parserName
	 * @return array|void
	 */
	public static function parse($filePath, $parserName) {
		$cachePath = self::$cachePathPrefix . '/' . $filePath;
		$hash = sha1_file($filePath);
		$miss = false;
		$result = phunk_cache_Disk::get($cachePath);
		if(!$result) $result = array();

		if(!isset($result[$parserName]) || !is_array($result[$parserName]) || !isset($result[$parserName]['version'])
		     || $result[$parserName]['version'] != phunk_parser_Abstract::VERSION || !isset($result[$parserName]['hash'])
		     || $result[$parserName]['hash'] != $hash || !isset($result[$parserName]['data'])) {
			$miss = true;
		}

		if($miss === true) {
			$result[$parserName] = array(
				'data' => phunk_parser_Runner::parse($parserName, phunk_Tokenizer::getTokens($filePath)),
				'version' => phunk_parser_Abstract::VERSION,
				'hash' => $hash
			);
			phunk_cache_Disk::set($cachePath, $result);
		}

		return $result[$parserName]['data'];
	}

	/**
	 * @param phunk_check_Abstract $check
	 * @return void
	 */
	protected function doPostProcess(phunk_check_Abstract $check) {
		try {
			$check->postProcess();
		}
		catch(phunk_check_exception_CheckFailException $e) {
			$this->handleFailure($e);
		}
	}

	/**
	 * @param phunk_check_exception_CheckFailException $exception
	 * @return void
	 */
	protected function handleFailure(phunk_check_exception_CheckFailException $exception) {
		if($exception instanceof phunk_check_exception_ExceptionCollection) {
			/** @var $exception phunk_check_exception_ExceptionCollection */
			$exceptions = $exception->getExceptions();
			foreach($exceptions as $exception) $this->handleFailure($exception);
			return;
		}

		$level = $exception->getFailLevel();
		if(!isset($this->problems[$level])) $this->problems[$level] = array();
		$this->problems[$level][] = $exception;
	}
}