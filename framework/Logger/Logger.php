<?php

namespace Achraf\framework\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class Logger
{
    private MonologLogger $logger;

    public function __construct(MonologLogger $logger, StreamHandler $streamHandler)
    {
        $this->logger = $logger->pushHandler($streamHandler);
    }

    public function info($message): void
    {
        $this->logger->info($message);
    }

    public function error($message): void
    {
        $this->logger->error($message);
    }

    public function warning($message): void
    {
        $this->logger->warning($message);
    }

    public function debug($message): void
    {
        $this->logger->debug($message);
    }
}
