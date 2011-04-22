<?php

function foo() {
	echo 'foo';
}

// This is fine
foo();

// Bar isn't defined!
bar();

// Native functions should be fine.
strpos("foo", "o");

class A {
	public function doSomething() {
		echo 'ok';
	}

	public static function doAnything() {
		echo 'hmm';
	}
}

// This is fine
$a = new A();

// This is fine.
$a->doSomething();

// Again, fine.
A::doAnything();

// Method isn't defined!
$a->doNothing();

// Method isn't defined!
A::doLazyThings();

// Class isn't defined!
B::doAnything();
