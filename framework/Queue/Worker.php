<?php

namespace Achraf\framework\Queue;

class Worker
{
    private RedisQueue $queue;

    public function __construct()
    {
        $this->queue = app()->get(RedisQueue::class);
    }

    public function run(): void
    {
        while (true) {
            $job = $this->queue->dequeue();

            if ($job) {
                try {
                    echo date('Y-m-d H:i:s')." Job found, processing...\n".get_class($job)."\n";
                    logToFile('info', "Job found, processing...\n".get_class($job)."\n");
                    $job->handle();
                    echo date('Y-m-d H:i:s')."Job processed\n";
                } catch (\Exception $e) {
                    echo date('Y-m-d H:i:s').'Job failed: '.$e->getMessage()."\n";
                    logToFile('error', 'Job failed: '.$e->getMessage());
                    if ($job->job_attempt < 3) {
                        $job->job_attempt++;
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
