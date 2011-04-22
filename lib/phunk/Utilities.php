<?php

/**
 * @package phunk
 */
class phunk_Utilities {
	/**
	 * This performs recursive deletion of a specified path.
	 *
	 * @static
	 * @param string $path
	 * @return void
	 */
	public static function removeDir($path) {
		if(is_dir($path)) {
			$dh = opendir($path);

			while(($file = readdir($dh)) !== false) {
				$fullPath = $path . '/' . $file;
				if($file !== '.' && $file != '..') {
					if(is_dir($fullPath)) {
						self::removeDir($fullPath);
					}
					if(is_file($fullPath)) unlink($fullPath);
				}
			}

			rmdir($path);
		}
	}
}