<?php

/** @var ClassLoader $loader */
use Composer\Autoload\ClassLoader;

define('APP_ROOT', dirname(__DIR__));

$loader = require_once APP_ROOT . '/vendor/autoload.php';
$loader->addPsr4('atphp\\drilex\\test_cases\\', __DIR__);
