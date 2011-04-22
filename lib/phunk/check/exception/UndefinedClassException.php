<?php

require_once 'phunk/check/exception/UndefinedException.php';

/**
 * @package phunk.check.exception
 */
class phunk_check_exception_UndefinedClassException extends phunk_check_exception_UndefinedException {
	/**
	 * @var string
	 */
	protected $flavour = 'class';
}