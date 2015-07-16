<?php

namespace atphp\drilex\commands;

use Bernard\Event\RejectEnvelopeEvent;
use Bernard\Message\DefaultMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use atphp\drilex\App;

class ConsumeCommand extends Command
{

    /** @var  App */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        parent::__construct('drilex:consume');
    }

    protected function configure()
    {
        $this
            ->setDescription('Message queue consumer')
            ->addOption('queue', null, InputOption::VALUE_REQUIRED, 'Name of message queue.')
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Number of message to be processed.', 100);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var SimpleRouter $router */
        $consumer = $this->app->getQueueConsumer();
        $router = $consumer->getRouter();
        $queueName = $input->getOption('queue');
        $queue = $this->app->getQueueFactory()->create($queueName);

        // Raise error instead of silently ignore it
        $consumer->getDispatcher()->addListener('bernard.reject', function (RejectEnvelopeEvent $event) {
            throw $event->getException();
        });

        // Route to our own processMessage method
        $router->add($queueName, function (DefaultMessage $message) use ($output) {
            $this->processMessage($output, $message);
        });

        $consumer->consume($queue, [
            'max-runtime'  => PHP_INT_MAX,
            'max-messages' => (int) $input->getOption('limit'),
        ]);
    }

    public function processMessage(OutputInterface $output, DefaultMessage $message)
    {
        throw new \RuntimeException('Implement logic here.');
    }

}
