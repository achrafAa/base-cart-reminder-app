<?php

namespace App\Jobs;

use Achraf\framework\Interfaces\QueueableInterface;
use Achraf\framework\Traits\Queueable;
use App\Services\CartReminderService;
use Exception;

class CreateReminderNotificationsJob implements QueueableInterface
{
    use Queueable;

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            app()->get(CartReminderService::class)->createReminderJobs();
        } catch (Exception $exception) {
            logToFile('error', $exception->getMessage());
        }
    }
}
