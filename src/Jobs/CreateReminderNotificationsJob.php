<?php

namespace App\Jobs;

use Achraf\framework\Queue\Queue;
use App\Services\CartReminderService;
use Exception;

class CreateReminderNotificationsJob extends Queue
{

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
