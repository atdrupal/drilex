<?php

namespace atphp\drilex\controllers;

use atphp\drilex\App;

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
            'content' => 'Welcome to <strong>Drilex</strong>!',
        ]);
    }

}
