<?php

require_once 'phunk/check/exception/UndefinedException.php';

/**
 * @package phunk.check.exception
 */
class phunk_check_exception_UndefinedMethodException extends phunk_check_exception_UndefinedException {
	/**
	 * @var string
	 */
	protected $flavour = 'method';
}