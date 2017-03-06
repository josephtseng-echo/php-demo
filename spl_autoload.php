<?php
$mapping = array(
		'Demo\Test\Test' => __DIR__ . '/Demo/Test/Test.php',
		'Package\Write' => __DIR__ . '/Package/Write.php',
		'Package\Read' => __DIR__ . '/Package/Read.php',
		'Package\EncryptDecrypt' => __DIR__ . '/Package/EncryptDecrypt.php'
);
spl_autoload_register(function ($class) use ($mapping) {
	if (isset($mapping[$class])) {

		include $mapping[$class];
	}
}, true);