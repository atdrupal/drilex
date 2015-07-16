<?php

namespace atphp\drilex\test_cases;

use atphp\drilex\App;

abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{

    public function getConfiguration()
    {
        return ['debug' => true] + require APP_ROOT . '/config.default.php';
    }

    public function getApplication()
    {
        return new App($this->getConfiguration());
    }

}
