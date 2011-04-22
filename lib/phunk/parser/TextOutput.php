<?php

require_once 'phunk/parser/Abstract.php';

/**
 * @package phunk.parser
 */
class phunk_parser_TextOutput extends phunk_parser_Abstract {
	/**
	 * @param array $tokens
	 * @return array
	 */
	public static function parse(array $tokens) {
		$inPHP = false;
		$nonCode = array();
		$lastLine = 1;
		$token = null;
		$tokenValue = null;

		foreach($tokens as $tokenInfo) {
			if(is_array($tokenInfo)) {
				$token = $tokenInfo[0];
				$tokenValue = $tokenInfo[1];
				$lastLine = $tokenInfo[2];

				switch($token) {
					case T_OPEN_TAG:
						$inPHP = true;
						break;
					case T_CLOSE_TAG:
						$inPHP = false;
						break;
				}
			}
			else {
				$tokenValue = $tokenInfo;
			}

			if(!$inPHP && $token !== T_CLOSE_TAG) $nonCode[] = array('line' => $lastLine, 'data' => $tokenValue);

			$tokenValue = null;
			$token = null;
		}

		return $nonCode;
	}
}