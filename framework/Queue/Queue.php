<?php

namespace Achraf\framework\Queue;

use Achraf\framework\Interfaces\QueueableInterface;
use Exception;
use Predis\Client as RedisClient;

class Queue implements QueueableInterface
{

    /**
     * @var int
     */
    public int $job_attempt;

    /**
     * @var RedisClient
     */
    public RedisClient $queue;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->queue = app()->get(RedisClient::class);

    }

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
     * @param  string  $queueName
     * @return RedisClient
     */
    public function onQueue(string $queueName): RedisClient
    {
        return $this->queue->onQueue($queueName);
    }


    /**
     * @return void
     */
    public function handle(): void
    {
        // TODO: Implement handle() method.
    }
}