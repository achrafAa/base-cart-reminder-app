<?php

namespace Achraf\framework\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class Logger
{
    /**
     * @var MonologLogger
     */
    private MonologLogger $logger;

    /**
     * @param  MonologLogger  $logger
     * @param  StreamHandler  $streamHandler
     */
    public function __construct(MonologLogger $logger, StreamHandler $streamHandler)
    {
        $this->logger = $logger->pushHandler($streamHandler);
    }

    /**
     * @param $message
     * @return void
     */
    public function info($message): void
    {
        $this->logger->info($message);
    }

    /**
     * @param $message
     * @return void
     */
    public function error($message): void
    {
        $this->logger->error($message);
    }

    /**
     * @param $message
     * @return void
     */
    public function warning($message): void
    {
        $this->logger->warning($message);
    }

    /**
     * @param $message
     * @return void
     */
    public function debug($message): void
    {
        $this->logger->debug($message);
    }
}
