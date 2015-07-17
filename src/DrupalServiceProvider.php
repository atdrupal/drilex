<?php

namespace atphp\drilex;

use atphp\drilex\controllers\DrupalController;
use atphp\drilex\drupal\Drupal;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DrupalServiceProvider implements ServiceProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(Container $c)
    {
        $c['drupal'] = function ($root, $siteDir, $baseUrl, array $settings = []) {
            $drupal = new Drupal($root, $siteDir, $settings);
            $drupal
                ->setDrupalCache($c['cache'])
                ->setTwig($c['twig']);

            return $drupal;
        };

        $c['ctr.drupal'] = function () use ($c) {
            return new DrupalController($c);
        };
    }

}
