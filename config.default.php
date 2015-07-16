<?php

/**
 * Example config.php
 *
 *    putenv('SITE_GA_CODE=UA-12344567-8');
 *
 *    $logger = new Monolog\Logger('atphp.drilex');
 *    $logger->pushHandler(new Monolog\Handler\ErrorLogHandler(), Monolog\Logger::DEBUG);
 *
 *    return ['debug'  => true, 'logger' => $logger] + require __DIR__ . '/config.default.php';
 */

return [
    // Template params
    'site_name'          => 'Drilex', # Also used in Console
    'site_version'       => 'dev',          # Also used in Console
    'site_footer'        => '© <a href="http://www.atphp.com/">@PHP</a> ' . date('Y'),
    'site_theme'         => '//bootswatch.com/yeti/bootstrap.css',
    'site_ga_code'       => getenv('SITE_GA_CODE'),

    // Drupal
    'drupal'             => [
        'root'     => getenv('DRUPAL_ROOT'),
        'site_dir' => getenv('DRUPAL_SITE_DIR'),
        'base_url' => getenv('DRUPAL_BASE_URL'),
        'settings' => [], // global variables for Drupal likes $databases, $cookie_domain, …
    ],

    // Database
    'db.options'         => ['driver' => 'pdo_sqlite', 'path' => __DIR__ . '/files/app.db'],

    // Application params
    'debug'              => false,

    // Authentication
    'security.firewalls' => [
        'login'   => ['pattern' => '^/user/login$'],
        'default' => [
            'pattern'   => '^/admin.*$',
            'logout'    => ['logout_path' => '/user/logout', 'with_csrf' => true],
            'anonymous' => true,
        ]
    ],

    // Authorization
    # 'security.access_rules' => [],
];
