<?php

namespace atphp\drilex\service_providers;

use Bernard\Driver\FlatFileDriver;
use Bernard\Middleware\MiddlewareBuilder;
use Bernard\Normalizer\DefaultMessageNormalizer;
use Bernard\Normalizer\EnvelopeNormalizer;
use Bernard\Producer;
use Bernard\QueueFactory\PersistentFactory;
use Bernard\Router\SimpleRouter;
use Bernard\Serializer;
use Normalt\Normalizer\AggregateNormalizer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use atphp\drilex\queue\Consumer;

class BernardServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $c An Container instance
     */
    public function register(Container $c)
    {
        $c['bernard.driver'] = function (Container $c) {
            return new FlatFileDriver($c['app.root'] . '/files/queue');
        };

        $c['bernard.serializer'] = function (Container $c) {
            return new Serializer(new AggregateNormalizer([
                new DefaultMessageNormalizer(),
                new EnvelopeNormalizer(),
                new GetSetMethodNormalizer()
            ]));
        };

        $c['bernard.factory'] = function (Container $c) {
            $driver = $c['bernard.driver'];
            $serializer = $c['bernard.serializer'];
            return new PersistentFactory($driver, $serializer);
        };

        $c['bernard.producer'] = function (Container $c) {
            $factory = $c['bernard.factory'];
            $dispatcher = $c['dispatcher'];
            return new Producer($factory, $dispatcher);
        };

        $c['bernard.router'] = function (Container $c) {
            return new SimpleRouter();
        };

        $c['bernard.consumer'] = function (Container $c) {
            $router = $c['bernard.router'];
            $dispatcher = $c['dispatcher'];
            return new Consumer($router, $dispatcher);
        };
    }

}
