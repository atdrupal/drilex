<?php

namespace atphp\drilex\controllers;

use atphp\drilex\App;
use Symfony\Component\HttpFoundation\Request;

class DrupalController
{

    /** @var App */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function action(Request $request)
    {
        $path = trim($request->getRequestUri(), '/') ?: '<front>';
        $response = $this->app->drupal()->handle($path);

        return $this->app->getTwig()->render('/pages/drupal.twig', [
            'head_title' => $response->getTitle(),
            'content'    => $response->getContent(),
            'messages'   => $response->getMessages()
        ]);
    }

    public function actionGetEntity($type, $id)
    {
        return $this->action(Request::create("{$type}/{$id}"));
    }

    public function actionGetHome()
    {
        return $this->action(Request::create('node/1'));
    }

    public function actionGetLogout()
    {
        $return = $this->action(Request::create('user/logout'));
        session_destroy();

        return $return;
    }

}
