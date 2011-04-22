<?php

require_once 'phunk/parser/Abstract.php';

/**
 * @package phunk.parser
 */
class phunk_parser_ClassDeclaration extends phunk_parser_Abstract {
	/**
	 * @param array $tokens
	 * @return array
	 */
	public static function parse(array $tokens) {
		$declarations = array();
		$inPHP = false;
		$inDeclaration = false;
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
					case T_CLASS:
						$inDeclaration = true;
						break;
					case T_STRING:
						if($inDeclaration) $declarations[] = array('line' => $lastLine, 'name' => $tokenValue);
						break;
					case T_WHITESPACE:
						break;
					default:
						$inDeclaration = false;
				}
			}
			else {
				$inDeclaration = false;
			}
		}

		return $declarations;
	}
}