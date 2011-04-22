<?php

require_once 'phunk/parser/Abstract.php';

/**
 * @package phunk.parser
 */
class phunk_parser_MethodDeclaration extends phunk_parser_Abstract {
	/**
	 * @param array $tokens
	 * @return array
	 */
	public static function parse(array $tokens) {
		$inPHP = false;
		$inClass = false;
		$inDeclaration = false;
		$inMethod = false;
		$classBraceTally = 0;
		$methodBraceTally = 0;
		$braceTally = 0;
		$lastLine = 1;
		$declarations = array();

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
						$inClass = true;
						$classBraceTally = $braceTally;
						break;
					case T_FUNCTION:
						$inDeclaration = true;
						break;
					case T_STRING:
						if($inDeclaration && $inClass && !$inMethod) {
							$declarations[] = array('line' => $lastLine, 'name' => $tokenValue);
							$inMethod = true;
							$methodBraceTally = $braceTally;
						}
						$inDeclaration = false;
						break;
					case T_WHITESPACE:
				        break;
					case T_CURLY_OPEN:
					case T_DOLLAR_OPEN_CURLY_BRACES:
					case T_STRING_VARNAME:
				        $braceTally++;
					default:
				        $inDeclaration = false;
				        break;
				}
			}
			else {
				switch($tokenInfo) {
					case '{':
				        $braceTally++;
				        break;
					case '}':
				        $braceTally--;
				        if($classBraceTally === $braceTally) $inClass = false;
				        if($methodBraceTally === $braceTally) $inMethod = false;
				        break;
					case ';':
				        if($inMethod && $methodBraceTally === $braceTally) $inMethod = false;
				        break;
				}
			}
		}

		return $declarations;
	}
}