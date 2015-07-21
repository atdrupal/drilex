<?php

namespace atphp\drilex\controllers;

use atphp\drilex\drupal\Drupal;
use Pimple\Container;
use Symfony\Component\HttpFoundation\Request;

class DrupalController
{

    /** @var Drupal */
    protected $drupal;

    /** @var  string */
    private $template = '/pages/drupal.twig';

    public function __construct(Container $c)
    {
        $this->drupal = $c['drupal'];
        $this->drupal->boot();
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function action(Request $request)
    {
        $response = $this->drupal->handle($request);

        return $this->drupal->getTwig()->render($this->template, [
            'head_title' => $response->getTitle(),
            'content'    => $response->getContent(),
            'messages'   => $response->getMessages()
        ]);
    }

    public function actionGetLogout()
    {
        $return = $this->action(Request::create('user/logout'));
        session_destroy();

        return $return;
    }

    public function actionGetEntity($type, $id)
    {
        return $this->action(Request::create("{$type}/{$id}"));
    }

}
