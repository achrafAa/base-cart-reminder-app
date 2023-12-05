<?php

namespace Achraf\framework\Queue;

class Worker
{
    private RedisQueue $queue;
    public function __construct() {
        $this->queue = app()->get(RedisQueue::class);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        while (true) {
            $job = $this->queue->dequeue();

            if ($job) {
                try {
                    echo "Job found, processing...\n" . get_class($job) . "\n";
                    logToFile('info', "Job found, processing...\n" . get_class($job) . "\n");
                    $job->handle();
                    echo "Job processed\n";
                } catch (\Exception $e) {
                    echo 'Job failed: ' . $e->getMessage() . "\n";
                    logToFile('error', 'Job failed: ' . $e->getMessage());
                    if ($job->attempts < 3) {
                        $job->attempts++;
                        $this->queue->enqueue($job);
                    }
                }
            } else {
                echo "No jobs found, sleeping for 5 seconds...\n";
                sleep(5);
            }
        }
    }
}
