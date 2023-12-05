<?php

namespace App\Jobs;

use Achraf\framework\Interfaces\QueueableInterface;
use Achraf\framework\Traits\Queueable;
use App\Services\CartReminderService;

class CreateReminderNotificationsJob implements QueueableInterface
{
    use Queueable;

    /**
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {
        try {
            app()->get(CartReminderService::class)->createReminderJobs();
        } catch (\Exception $exception) {
            logToFile('error', $exception->getMessage());
        }
    }
}
