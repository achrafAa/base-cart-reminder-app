<?php

namespace Achraf\framework\Traits;

use Achraf\framework\Queue\RedisQueue;
use Exception;

trait Queueable
{
    public RedisQueue $queue;

    /**
     * @return void
     */
    public function dispatch(): void
    {
        try {
            $this->queue->enqueue($this);
        } catch (Exception $exception) {
            logToFile('error', $exception->getMessage());
        }
    }

    /**
     * @param string $queueName
     * @return RedisQueue
     */
    public function onQueue(string $queueName): RedisQueue
    {
        return $this->queue->onQueue($queueName);
    }
}
