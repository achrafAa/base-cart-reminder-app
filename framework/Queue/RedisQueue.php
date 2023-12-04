<?php

namespace Achraf\framework\Queue;

use Achraf\framework\Interfaces\QueueableInterface;
use Predis\Client;

class RedisQueue
{
    protected Client $client;
    private String $queueName = 'default-queue';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param QueueableInterface $job
     * @return void
     */
    public function enqueue(QueueableInterface $job): void
    {
        $this->client->rpush($this->queueName, [serialize($job)]);
    }

    /**
     * @return QueueableInterface|null
     */
    public function dequeue(): ?QueueableInterface
    {
        $job = $this->client->lpop($this->queueName);
        if ($job) {
            return unserialize($job);
        }

        return null;
    }

    /**
     * @param string $queueName
     * @return $this
     */
    public function onQueue(String $queueName): self
    {
        $this->queueName = $queueName;

        return $this;
    }
}
