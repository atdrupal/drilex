<?php

namespace atphp\drilex\service_providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use atphp\drilex\App;

class DoctrineOrmServiceProvider implements ServiceProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(Container $c)
    {
        $c['orm.default_cache'] = function (App $c) {
            return $c->getCache();
        };

        $c->register(new \Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider(), [
            'orm.proxies_dir' => $c['app.root'] . '/files/proxies',
            'orm.em.options'  => [
                'mappings' => $c['orm.mappings']
            ],
        ]);
    }

}
