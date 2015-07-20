<?php

namespace atphp\drilex;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;

class DrupalControllerProvider implements ControllerProviderInterface
{

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     * @return ControllerCollection
     */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $route */
        $route = $app['controllers_factory'];

        $route->get('/user', 'ctr.drupal:action');
        $route->match('/user/login', 'ctr.drupal:action')->method('GET|POST')->bind('user-login');
        $route->get('/user/password', 'ctr.drupal:action')->method('GET|POST')->bind('user-password');
        $route->get('/user/logout', 'ctr.drupal:actionGetLogout')->method('GET')->bind('user-logout');
        $route->match('/user/{uid}/edit', 'ctr.drupal:action')->method('GET|POST')->bind('user-edit');
        $route->get('/{type}/{id}', 'ctr.drupal:actionGetEntity')->bind('drupal-entity');

        return $route;
    }

}
