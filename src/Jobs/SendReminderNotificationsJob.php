<?php

namespace App\Jobs;

use Achraf\framework\Queue\Queue;
use App\Services\CartReminderService;
use Exception;

class SendReminderNotificationsJob extends Queue
{

    public int $attempt;
    private int $cartId;

    public function __construct(int $cartId, int $attempt)
    {
        $this->cartId = $cartId;
        $this->attempt = $attempt;
        parent::__construct();
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
