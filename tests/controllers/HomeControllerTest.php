<?php

namespace atphp\drilex\test_cases\controllers;

use Symfony\Component\HttpFoundation\Request;
use atphp\drilex\test_cases\BaseTestCase;

class HomeControllerTest extends BaseTestCase
{

    public function testActionGet()
    {
        $app = $this->getApplication();
        $response = $app->handle(Request::create('/'));
        $this->assertContains('Welcome to <strong>Drilex</strong>!', $response->getContent());
    }

}
