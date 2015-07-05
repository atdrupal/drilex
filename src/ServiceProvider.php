<?php

namespace vendor_name\project_name;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use vendor_name\project_name\service_providers\BernardServiceProvider;
use vendor_name\project_name\service_providers\DoctrineOrmServiceProvider;
use vendor_name\project_name\service_providers\JmsSerializerServiceProvider;

class ServiceProvider implements ServiceProviderInterface, BootableProviderInterface, EventListenerProviderInterface
{

    protected $controllers = ['ctr.home'];
    protected $commands    = ['command.consume'];
    protected $ormMappings = [
        'vendor_name\\project_name\\entity' => '[%app.root]/src/models',
    ];

    /**
     * {@inheritdoc}
     */
    public function register(Container $c)
    {
        $c->register(new TwigServiceProvider(), [
            'twig.path'           => $c['app.root'] . '/resources/views',
            'twig.form.templates' => ['bootstrap_3_horizontal_layout.html.twig'],
        ]);
        $c->register(new ServiceControllerServiceProvider());
        $c->register(new RoutingServiceProvider());
        $c->register(new FormServiceProvider());
        $c->register(new SecurityServiceProvider(), ['security.firewalls' => $c['security.firewalls']]);
        $c->register(new DoctrineServiceProvider(), ['db.options' => $c['db.options']]);
        $c->register(new BernardServiceProvider(), ['app.root' => $c['app.root']]);
        $c->register(new JmsSerializerServiceProvider(), ['serializer.cacheDir' => $c['app.root'] . '/files/cache/jms.serializer']);

        // Doctrine ORM
        $c['orm.mappings'] = function (Container $c) {
            return array_map(function ($ns, $path) use ($c) {
                $path = str_replace('[%app.root]', $c['app.root'], $path);
                return ['type' => 'annotation', 'namespace' => $ns, 'path' => $path];
            }, array_keys($this->ormMappings), array_values($this->ormMappings));
        };

        $c->register(new DoctrineOrmServiceProvider(), [
            'app.root'        => $c['app.root'],
            'orm.em.mappings' => $c['orm.mappings']
        ]);

        $c['twig'] = $c->extend('twig', function (\Twig_Environment $twig, Container $c) {
            $twig->addGlobal('app', $c);
            return $twig;
        });

        // Define controller services
        foreach ($this->controllers as $service) {
            $c[$service] = function (Container $c) use ($service) {
                // Change class name from ctr.home to -> \vendor_name\project_name\controllers\HomeController
                $class = str_replace(['ctr.', '.'], ['', ' '], $service);
                $class = __NAMESPACE__ . '\\controllers\\' . str_replace(' ', '', ucwords($class)) . 'Controller';
                return new $class($c);
            };
        }

        // Define command services
        foreach ($this->commands as $service) {
            $c[$service] = function (Container $c) use ($service) {
                // Change class name from command.consume to -> \vendor_name\project_name\commands\ConsumeCommand
                $class = str_replace(['command.', '.'], ['', ' '], $service);
                $class = __NAMESPACE__ . '\\commands\\' . str_replace(' ', '', ucwords($class)) . 'Command';
                return new $class($c);
            };
        }
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(Container $container, EventDispatcherInterface $dispatcher)
    {
        // TODO: Implement subscribe() method.
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }

}
