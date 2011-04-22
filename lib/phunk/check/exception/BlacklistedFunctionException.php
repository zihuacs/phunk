<?php

require_once 'phunk/check/exception/BlacklistedException.php';

/**
 * @package phunk.check.exception
 */
class phunk_check_exception_BlacklistedFunctionException extends phunk_check_exception_BlacklistedException {
	/**
	 * @var string
	 */
	protected $flavour = 'function';
}