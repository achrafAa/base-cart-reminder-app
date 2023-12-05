<?php

namespace App\Jobs;

use Achraf\framework\Traits\Queueable;
use App\Services\CartReminderService;
use Exception;

class SendReminderNotificationsJob
{
    use Queueable;

    private int $id;

    private int $cartId;

    public function __construct(int $cartId, private readonly int $attempt)
    {
        $this->cartId = $cartId;
    }

    /**
     * handle the job.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            app()->get(CartReminderService::class)->sendReminderNotification($this->cartId, $this->attempt);
        } catch (Exception $exception) {
            logToFile('error', $exception->getMessage());
            throw $exception;
        }
    }
}
