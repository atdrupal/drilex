<?php

namespace atphp\drilex\traits;

use Bernard\Producer;
use Bernard\QueueFactory\PersistentFactory;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Console\Application as Console;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use atphp\drilex\queue\Consumer;

trait GetSetTrait
{

    public function getAppRoot()
    {
        return $this['app.root'];
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if (null === $this['logger']) {
            $this['logger'] = new NullLogger();
        }
        return $this['logger'];
    }

    /**
     * @param LoggerInterface $logger
     * @return self
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this['logger'] = $logger;
        return $this;
    }

    /**
     * @return Cache
     */
    public function getCache()
    {
        if (!isset($this['cache']) || (null === $this['cache'])) {
            $this['cache'] = new FilesystemCache($this->getAppRoot() . '/files/cache');
        }
        return $this['cache'];
    }

    /**
     * @param Cache $cache
     * @return self
     */
    public function setCache($cache)
    {
        $this['cache'] = $cache;
        return $this;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this['dispatcher'];
    }

    /**
     * @return Console
     */
    public function getConsole()
    {
        if (!isset($this['console']) || (null === $this['console'])) {
            $name = isset($this['site_name']) ? $this['site_name'] : '@PHP';
            $version = isset($this['site_version']) ? $this['site_version'] : 'dev';
            $this['console'] = new Console($name, $version);
        }
        return $this['console'];
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        return $this['twig'];
    }

    /**
     * @param Console $console
     * @return self
     */
    public function setConsole($console)
    {
        $this['console'] = $console;
        return $this;
    }

    /**
     * @return Connection
     */
    public function getDb()
    {
        return $this['db'];
    }

    /**
     * @return Configuration
     */
    public function getDbConfig()
    {
        return $this['db.config'];
    }

    /**
     * @return EventManager
     */
    public function getDbEventManager()
    {
        return $this['db.event_manager'];
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager($name = 'default')
    {
        return isset($this["orm.em.{$name}"]) ? $this["orm.em.{$name}"] : $this['orm.em'];
    }

    /**
     * @return Producer
     */
    public function getQueueProducer()
    {
        return $this['bernard.producer'];
    }

    /**
     * @return PersistentFactory
     */
    public function getQueueFactory()
    {
        return $this['bernard.factory'];
    }

    /**
     * @return Consumer
     */
    public function getQueueConsumer()
    {
        return $this['bernard.consumer'];
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer()
    {
        return $this['serializer'];
    }

}
