<?php

require __DIR__ . '/../vendor/autoload.php';

// app base directory
define('TESTS_DIR',  __DIR__);

$loader = new Nette\Loaders\RobotLoader;
$loader->addDirectory(__DIR__ . '/../src');
$loader->setCacheStorage(new Nette\Caching\Storages\FileStorage(__DIR__ . '/tmp'));
$loader->register();

?>
