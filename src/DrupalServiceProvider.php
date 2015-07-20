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
        $c['drupal'] = function (Container $c) {
            $root = $c['drupal.options']['root'];
            $siteDir = $c['drupal.options']['site_dir'];
            $baseUrl = $c['drupal.options']['base_url'];
            $settings = $c['drupal.options']['settings'];

            $drupal = new Drupal($root, $siteDir, $baseUrl, $settings);

            drilex_dispatcher($c['dispatcher']);
            drilex_drupal($drupal);

            $drupal
                // ->setDrupalCache($c['cache'])
                ->setTwig($c['twig']);

            return $drupal;
        };

        $c['ctr.drupal'] = function () use ($c) {
            return new DrupalController($c);
        };
    }

}
