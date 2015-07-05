<?php

namespace vendor_name\project_name;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;

class ControllerProvider implements ControllerProviderInterface
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

        $route->get('/', 'ctr.home:get')->bind('name');
        $route->get('/login', 'ctr.user:getLogin')->bind('user-login');
        $route->get('/logout', 'ctr.user:getLogout')->bind('user-logout');

        return $route;
    }

}
