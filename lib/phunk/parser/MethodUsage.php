<?php

require_once 'phunk/parser/Abstract.php';

/**
 * @package phunk.parser
 */
class phunk_parser_MethodUsage extends phunk_parser_Abstract {
	/**
	 * @param array $tokens
	 * @return array
	 */
	public static function parse(array $tokens) {
		$inPHP = false;
		$inDeclaration = false;
		$inInstantiation = false;
		$isObjectOperation = false;
		$functionName = null;
		$lastLine = 1;
		$usages = array();

		foreach($tokens as $tokenInfo) {
			if(is_array($tokenInfo)) {
				$token = $tokenInfo[0];
				$tokenValue = $tokenInfo[1];
				$lastLine = $tokenInfo[2];

				if(!$inPHP && $token !== T_OPEN_TAG) continue;
				if($token !== T_STRING && $token !== T_WHITESPACE) $functionName = null;

				switch($token) {
					case T_OPEN_TAG:
						$inPHP = true;
						break;
					case T_CLOSE_TAG:
						$inPHP = false;
						break;
					case T_FUNCTION:
						$inDeclaration = true;
						break;
					case T_NEW:
				        $inInstantiation = true;
				        break;
					case T_DOUBLE_COLON:
					case T_OBJECT_OPERATOR:
				        $isObjectOperation = true;
				        break;
					case T_STRING:
						if(!$inDeclaration && !$inInstantiation) $functionName = $tokenValue;
						break;
					case T_WHITESPACE:
				        break;
					default:
						$inDeclaration = false;
						$inInstantiation = false;
						$isObjectOperation = false;
				        break;
				}
			}
			else {
				switch($tokenInfo) {
					case '(':
						if($functionName && $isObjectOperation) $usages[] = array('line' => $lastLine, 'name' => $functionName);
				        break;
				}

				$functionName = null;
				if($tokenInfo != '&') $inDeclaration = false;
				$inInstantiation = false;
				$isObjectOperation = false;
			}
		}

		return $usages;
	}
}