<?php

/**
 * @package phunk.parser
 */
abstract class phunk_parser_Abstract {
	/**
	 * @const string
	 */
	const FUNCTION_USAGE = 'FunctionUsage';

	/**
	 * @const string
	 */
	const FUNCTION_DECLARATION = 'FunctionDeclaration';

	/**
	 * @const string
	 */
	const METHOD_USAGE = 'MethodUsage';

	/**
	 * @const string
	 */
	const METHOD_DECLARATION = 'MethodDeclaration';

	/**
	 * @const string
	 */
	const CLASS_USAGE = 'ClassUsage';

	/**
	 * @const string
	 */
	const CLASS_DECLARATION = 'ClassDeclaration';

	/**
	 * @const string
	 */
	const TEXT_OUTPUT = 'TextOutput';

	/**
	 * @const int
	 */
	const VERSION = '1.0';

	/**
	 * @abstract
	 * @param array $tokens
	 * @return array
	 */
	abstract public static function parse(array $tokens);
}