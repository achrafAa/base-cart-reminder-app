<?php

namespace Achraf\framework\Queue;

use Achraf\framework\Interfaces\QueueableInterface;
use Exception;

class Queue implements QueueableInterface
{
    public int $job_attempt;

    public RedisQueue $queue;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->queue = app()->get(RedisQueue::class);

    }

    public function dispatch(): void
    {
        try {
            $this->queue->enqueue($this);
        } catch (Exception $exception) {
            logToFile('error', $exception->getMessage());
        }
    }

    public function onQueue(string $queueName): RedisQueue
    {
        return $this->queue->onQueue($queueName);
    }

    public function handle(): void
    {
        // TODO: Implement handle() method.
    }
}
