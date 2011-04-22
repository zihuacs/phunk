<?php

/**
 * @throws InvalidArgumentException
 * @package phunk
 */
class phunk_Logger {
	/**
	 * @var bool
	 */
	protected static $logLevel = self::LEVEL_SILENT;

	/**
	 * @var bool
	 */
	protected static $displayLevel = self::LEVEL_NORMAL;

	/**
	 * @var array
	 */
	protected static $validLevels = array(
		self::LEVEL_SILENT,
		self::LEVEL_QUIET,
		self::LEVEL_NORMAL,
		self::LEVEL_VERBOSE
	);

	/**
	 * @const int
	 */
	const LEVEL_SILENT = 0;

	/**
	 * @const int
	 */
	const LEVEL_QUIET = 1;

	/**
	 * @const int
	 */
	const LEVEL_NORMAL = 2;

	/**
	 * @const int
	 */
	const LEVEL_VERBOSE = 3;

	/**
	 * @const int
	 */
	const SHORT_CHARS_PER_LINE = 60;

	/**
	 * @const string
	 */
	const SHORT_CHAR_SUCCESS = '.';

	/**
	 * @const string
	 */
	const SHORT_CHAR_ERROR = 'F';

	/**
	 * @const string
	 */
	const SHORT_CHAR_WARNING = 'W';

	/**
	 * @static
	 * @throws InvalidArgumentException
	 * @param int $level
	 * @return void
	 */
	public static function setLogLevel($level) {
		if(!in_array($level, self::$validLevels)) throw new InvalidArgumentException();
		self::$logLevel = $level;
	}

	/**
	 * @static
	 * @throws InvalidArgumentException
	 * @param int $level
	 * @return void
	 */
	public static function setDisplayLevel($level) {
		if(!in_array($level, self::$validLevels)) throw new InvalidArgumentException();
		self::$displayLevel = $level;
	}

	/**
	 * @param string $char
	 * @return void
	 */
	public static function displayShort($char) {
		static $displayedShortChars = 0;
		self::write($char, self::LEVEL_NORMAL, true);
		if(++$displayedShortChars === self::SHORT_CHARS_PER_LINE) {
			self::write();
			$displayedShortChars = 0;
		}
	}

	/**
	 * @static
	 * @param string $message
	 * @param int $level
	 * @param bool $noNewLine
	 * @return void
	 */
	public static function write($message = '', $level = self::LEVEL_NORMAL, $noNewLine = false) {
		$output = $message . (!$noNewLine ? PHP_EOL : '');
		if(self::$displayLevel >= $level) print $output;
		if(self::$logLevel >= $level) file_put_contents('/var/log/phunk.log', $output, FILE_APPEND);
	}
}