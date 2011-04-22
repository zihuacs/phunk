<?php

require_once 'phunk/parser/Abstract.php';

/**
 * @package phunk.parser
 */
class phunk_parser_ClassUsage extends phunk_parser_Abstract {
	/**
	 * @param array $tokens
	 * @return array
	 */
	public static function parse(array $tokens) {
		$inPHP = false;
		$usages = array();
		$lastString = null;
		$hadNew = false;
		$lastLine = 1;

		foreach($tokens as $tokenInfo) {
			if(is_array($tokenInfo)) {
				$token = $tokenInfo[0];
				$tokenValue = $tokenInfo[1];
				$lastLine = $tokenInfo[2];

				if(!$inPHP && $token !== T_OPEN_TAG) continue;

				switch($token) {
					case T_OPEN_TAG:
						$inPHP = true;
						break;
					case T_CLOSE_TAG:
						$inPHP = false;
						break;
					case T_STRING:
						if($hadNew) {
							$usages[] = array('line' => $lastLine, 'name' => $tokenValue);
							$lastString = null;
							$hadNew = false;
						}
						else $lastString = $tokenValue;
						break;
					case T_DOUBLE_COLON:
						if($lastString) $usages[] = array('line' => $lastLine, 'name' => $lastString);
						$lastString = null;
						break;
					case T_NEW:
						$hadNew = true;
						break;
					case T_WHITESPACE:
						break;
					default:
						$lastString = null;
						$hadNew = false;
				}
			}
		}

		return $usages;
	}
}