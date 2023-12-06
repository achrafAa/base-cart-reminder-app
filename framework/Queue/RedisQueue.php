<?php

namespace Achraf\framework\Queue;

use Achraf\framework\Interfaces\QueueableInterface;
use Predis\Client;

class RedisQueue
{
    protected Client $client;

    private string $queueName = 'default-queue';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function enqueue(QueueableInterface $job): void
    {
        $this->client->rpush($this->queueName, [serialize($job)]);
    }

    public function dequeue(): ?QueueableInterface
    {
        $job = $this->client->lpop($this->queueName);
        if ($job) {
            return unserialize($job);
        }

        return null;
    }

    /**
     * @return $this
     */
    public function onQueue(string $queueName): self
    {
        $this->queueName = $queueName;

        return $this;
    }
}
