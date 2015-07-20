<?php

use atphp\drilex\drupal\Drupal;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @param Drupal $drupal
 * @return Drupal
 */
function drilex_drupal(Drupal $drupal = null)
{
    static $_drupal;

    if (null !== $drupal) {
        $_drupal = $drupal;
    }

    return $_drupal;
}

function drilex_event(array $params = [])
{
    return new GenericEvent(drilex_drupal() . $params);
}

/**
 * @param EventDispatcherInterface $dispatcher
 * @return EventDispatcherInterface
 */
function drilex_dispatcher(EventDispatcherInterface $dispatcher = null)
{
    static $_dispatcher = null;

    if (null !== $dispatcher) {
        $_dispatcher = $dispatcher;
    }

    return $_dispatcher;
}

/**
 * Implements hook_init()
 */
function node_init()
{
    return drilex_dispatcher()->dispatch('drupal.init');
}

/**
 * Implements hook_exit().
 *
 * @param string $destination
 */
function system_exit($destination)
{
    drilex_dispatcher()->dispatch('exit', drilex_event(['destination' => $destination]));
}
