<?php

namespace vendor_name\project_name\test_cases;

use Bernard\Consumer;
use Bernard\Producer;
use Bernard\QueueFactory\PersistentFactory;
use Doctrine\Common\Cache\Cache;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;

class AppTest extends BaseTestCase
{

    public function testServices()
    {
        $app = $this->getApplication();
        $this->assertEquals(APP_ROOT, $app->getAppRoot());
        $this->assertTrue($app->getCache() instanceof Cache);
        $this->assertTrue($app->getConsole() instanceof Application);
        $this->assertTrue($app->getDb() instanceof Connection);
        $this->assertTrue($app->getLogger() instanceof LoggerInterface);
        $this->assertTrue($app->getEntityManager() instanceof EntityManagerInterface);
        $this->assertTrue($app->getQueueConsumer() instanceof Consumer);
        $this->assertTrue($app->getQueueFactory() instanceof PersistentFactory);
        $this->assertTrue($app->getQueueProducer() instanceof Producer);
        $this->assertTrue($app->getSerializer() instanceof SerializerInterface);
    }

}
