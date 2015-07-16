<?php

use atphp\drilex\App;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';
(new App(require dirname(__DIR__) . '/config.php'))->run();
