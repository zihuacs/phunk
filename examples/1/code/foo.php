<?php

function foo() {
	echo 'foo';
}

foo();

class C {
	public function okGo() {
		echo 't';
	}
}

strpos("foo", "o");

// exec will be blacklisted
exec("echo 1");

// mysqli
$x = new mysqli("");

$x = new DOMDocument("1.0", "UTF-8");

$p = new PDO("");

// blacklist PDO direct querying
$p->query("SELECT 1");

// also bad
PDO::query('SELECT 1');