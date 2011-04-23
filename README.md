phunk
=====

phunk is a static analysis tool for PHP. phunk currently can check for various problem states which would not typically
be picked up by traditional lint tools, or similar. phunk is written entirely in PHP.

Checks
------

phunk perform analysis on a number of different potential problems:
* undefined function / method / class usage: phunk searches for usages of functions / methods / classes which it cannot
   find declarations for and are not native to PHP,
* blacklisted function / method / class usage: phunk allows you to specify lists of functions / methods / classes
   which you do not permit usage of in your codebase,
* non-code portions: phunk can search for non-php portions of text in areas they don't belong - it can scan all your
  classes for any text laying outside of the PHP tags.

Various checks contain whitelisting options to deal with cases where false-positives are caught (this is particularly
a problem where the __call magic method is utilised).

Please note that the checks are rudimentary by nature and will not catch all problems.

Suites
------

To have phunk run any checks over a codebase, you need to write a suite, specifying which checks you wish to be
performed, and customising the checks as necessary. A suite must extend `phunk_Suite` and implement the static method
`suite`. An example suite:

```php
<?php
require_once 'lib/phunk/Suite.php';

class Checks extends phunk_Suite {
	public static function suite() {
		// Setup an undefined function check
		require_once 'phunk/check/UndefinedFunction.php';
		$check = new phunk_check_UndefinedFunction();
		$check->addDependencyPath('/path/to/codebase/');
		$check->addCheckPath('/path/to/codebase/');
		self::addCheck($check);
	}
}
?>
```

Note that you may wish to utilise `self::addIncludePathsAsDependencies($check);` if your codebase relies on libraries
placed outside the normal scope of things (for example, PHPUnit classes are typically made available from within
"/usr/share/php/" on debian systems.) Alternatively, specify the paths manually.

Running phunk
-------------
Simply point phunk to the suite in the following fashion:

	./phunk <suite.php>

On larger codebases the first run may take some time - note, however, that phunk performs on-disk caching to speed up
subsequent runs.

Examples
--------

Some example suites exist for your perusal under the "examples" directory. A brief explanation of each:
* example 0: this performs checks for undefined functions / methods / classes for files under the "code" directory -
  view the "foo.php" file to see the various issues at hand.
* example 1: this performs checks for blacklisted functions / methods / classes for files under the "code" directory -
  view the suite to see what has been banned, and check the "foo.php" file to see that various banned things are being
  used,
* example 2: this performs checks for non-code portions for files under the "code" directory - you'll noticed "bar.php"
  is missing a left-angle bracket from the opening PHP tag - phunk will pick this up,
* example 3: this performs the undefined functions / methods / classes checks, and the non-code portion check across
  the phunk codebase - this should hopefully pass(!).

To try the examples, simply run:

	./phunk examples/#/Checks.php

Example run:

	$ ./phunk examples/0/Checks.php
	phunk v1.00

	F

	There were 4 errors:

	In /home/user/phunk/examples/0/code/foo.php:
	1) "bar" on line 11 is an undefined function
	2) "doNothing" on line 36 is an undefined method
	3) "doLazyThings" on line 39 is an undefined method
	4) "B" on line 42 is an undefined class

	FAILED!
