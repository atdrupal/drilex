<?php

ini_set('display_errors', 1); # prevent CLI app silent error
error_reporting(-1);

use Doctrine\DBAL\Tools\Console\ConsoleRunner as DBAL;
use Doctrine\ORM\Tools\Console\ConsoleRunner as ORM;
use atphp\drilex\App;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * @return \Symfony\Component\Console\Application
 */
function main()
{

    $app = new App(require __DIR__ . '/config.php');

    $console = $app->getConsole();

    // Doctrine commands
    $console->setHelperSet(DBAL::createHelperSet($app->getDb()));
    $console->setHelperSet(ORM::createHelperSet($app->getEntityManager()));
    DBAL::addCommands($console);
    ORM::addCommands($console);

    // Our custom commands
    foreach ($app->keys() as $key) {
        if (false !== strpos($key, 'command.')) {
            $console->add($app[$key]);
        }
    }

    return $console;
}

main()->run();
