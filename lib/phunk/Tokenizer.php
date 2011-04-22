<?php

/**
 * @package phunk
 */
class phunk_Tokenizer {
	/**
	 * @static
	 * @param string $filePath
	 * @return array
	 */
	public static function getTokens($filePath) {
		if(!is_file($filePath)) throw new InvalidArgumentException();
		return token_get_all(file_get_contents($filePath));
	}


}