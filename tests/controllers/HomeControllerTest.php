<?php

namespace vendor_name\project_name\test_cases\controllers;

use Symfony\Component\HttpFoundation\Request;
use vendor_name\project_name\test_cases\BaseTestCase;

class HomeControllerTest extends BaseTestCase
{

    public function testActionGet()
    {
        $app = $this->getApplication();
        $response = $app->handle(Request::create('/'));
        $this->assertContains('Welcome to <strong>Project Name</strong>!', $response->getContent());
    }

}
