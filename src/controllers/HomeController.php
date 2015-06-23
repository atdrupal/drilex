<?php

namespace vendor_name\project_name\controllers;

use vendor_name\project_name\App;

class HomeController
{

    /** @var App */
    private $app;
    
    public function __construct(App $app)
    {
        $this->app = $app;
    }
    
    public function get()
    {
        return $this->app->getTwig()->render('pages/index.twig', [
            'content' => 'Welcome to <strong>Project Name</strong>!',
        ]);
    }

}
