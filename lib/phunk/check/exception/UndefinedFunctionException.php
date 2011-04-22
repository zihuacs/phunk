<?php

require_once 'phunk/check/exception/UndefinedException.php';

/**
 * @package phunk.check.exception
 */
class phunk_check_exception_UndefinedFunctionException extends phunk_check_exception_UndefinedException {
	/**
	 * @var string
	 */
	protected $flavour = 'function';
}