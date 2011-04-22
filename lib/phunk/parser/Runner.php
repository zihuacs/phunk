<?php

/**
 * @throws InvalidArgumentException
 * @package phunk.parser
 */
class phunk_parser_Runner {
	/**
	 * @var array
	 */
	protected static $validParsers = array(
		phunk_parser_Abstract::FUNCTION_USAGE,
		phunk_parser_Abstract::FUNCTION_DECLARATION,
		phunk_parser_Abstract::METHOD_USAGE,
		phunk_parser_Abstract::METHOD_DECLARATION,
		phunk_parser_Abstract::CLASS_USAGE,
		phunk_parser_Abstract::CLASS_DECLARATION,
		phunk_parser_Abstract::TEXT_OUTPUT
	);

	/**
	 * @var array
	 */
	protected static $parsers = array();

	/**
	 * @static
	 * @throws InvalidArgumentException
	 * @param string $name
	 * @param array $tokens
	 * @return array
	 */
	public static function parse($name, array $tokens) {
		// Construct the class name
		$className = 'phunk_parser_' . $name;

		if(!isset(self::$parsers[$className])) {
			require_once 'phunk/parser/' . $name . '.php';
			if(!in_array($name, self::$validParsers) || !class_exists($className)) throw new InvalidArgumentException($name . ' is not a valid parser');

			// Instantiate the class
			self::$parsers[$className] = new ReflectionMethod($className, 'parse');
		}

		/** @var $parser ReflectionMethod */
		$parser = self::$parsers[$className];

		return $parser->invoke(NULL, $tokens);
	}
}