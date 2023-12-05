<?php

namespace App\Services;

use Achraf\framework\Mailer\Mailer;
use App\Enums\CartReminderTimeInterval;
use App\Jobs\SendReminderNotificationsJob;
use App\Models\Cart;
use Carbon\Carbon;
use Exception;

class CartReminderService
{
    /**
     * @return void
     */
    public function CreateReminderJobs(): void
    {
        $this->CreateReminderJobsForFirstAttempt();
        $this->CreateReminderJobsForSecondAttempt();
        $this->CreateReminderJobsForThirdAttempt();
        $this->DeleteCarts();
    }

    /**
     * @return void
     */
    private function CreateReminderJobsForFirstAttempt(): void
    {
        try {
            Cart::query()
                ->select('id')
                ->where('created_at', '<=', Carbon::now()->subHours(CartReminderTimeInterval::FIRST_ATTEMPT->value)->toDateTimeString())
                ->where('reminder_attempt', 0)
                ->chunk(100, function ($carts) {
                    foreach ($carts as $cart) {
                        (new SendReminderNotificationsJob($cart->id, 1))->dispatch();
                    }
                });
        } catch (Exception $exception) {
            $this->logError($exception);
        }
    }

    /**
     * @return void
     */
    private function CreateReminderJobsForSecondAttempt(): void
    {
        try {
            Cart::query()
                ->where('reminder_attempt', 1)
                ->whereHas('cartReminderNotification', function ($query) {
                    $query
                        ->latest()
                        ->where('sent_at', '<=', Carbon::now()->subHours(CartReminderTimeInterval::SECOND_ATTEMPT->value)->toDateTimeString());
                })
                ->chunk(100, function ($carts) {
                    foreach ($carts as $cart) {
                        (new SendReminderNotificationsJob($cart->id, 2))->dispatch();
                    }
                });
        } catch (Exception $exception) {
            $this->logError($exception);
        }
    }

    /**
     * @return void
     */
    private function CreateReminderJobsForThirdAttempt(): void
    {
        try {
            Cart::query()
                ->where('reminder_attempt', 2)
                ->whereHas('cartReminderNotification', function ($query) {
                    $query
                        ->latest()
                        ->where('sent_at', '<=', Carbon::now()->subHours(CartReminderTimeInterval::THIRD_ATTEMPT->value)->toDateTimeString());
                })
                ->chunk(100, function ($carts) {
                    foreach ($carts as $cart) {
                        (new SendReminderNotificationsJob($cart->id, 3))->dispatch();
                    }
                });
        } catch (Exception $exception) {
            $this->logError($exception);
        }
    }

    /**
     * @return void
     */
    public function DeleteCarts(): void
    {
        try {
            Cart::query()
                ->where('reminder_attempt', 2)
                ->whereHas('cartReminderNotification', function ($query) {
                    $query
                        ->latest()
                        ->where('sent_at', '<=', Carbon::now()->subHours(CartReminderTimeInterval::DELETE->value)->toDateTimeString());
                })
                ->delete();
        } catch (Exception $exception) {
            $this->logError($exception);
        }
    }

    /**
     * @param  int  $cartId
     * @param  int  $attempt
     * @return void
     */
    public function sendReminderNotification(int $cartId, int $attempt): void
    {
        // we can use attempt to send different emails

        try {
            $cart = Cart::query()->find($cartId);
            if (! $cart) {
                throw new Exception(sprintf('Cart with id %s not found', $cartId));
            }
            if ($cart->cartReminderNotification && $cart->cartReminderNotification->attempt_count >= $attempt) {
                return;
            }
            $cart->cartReminderNotification()
                ->Create([
                    'attempt' => $attempt,
                    'sent_at' => Carbon::now(),
                ])
                ->save();
            $cart->reminder_attempt = $attempt;
            $cart->save();
            app()->get(Mailer::class)->sendEmail(
                $cart->customer_email,
                'Cart Reminder',
                sprintf(
                    '<h1>Hello %s, you have items in your cart</h1>
                           <p>Click <a href="%s">here</a> to checkout</p>',
                    $cart->customer_fullname,
                    sprintf('%s/cart/%s', config('APP_URL'), $cart->id)
                )
            );
            logToFile('info', 'Email sent successfully! cart: '.$cart->id.' - '.$attempt.' attempt');
        } catch (Exception $exception) {
            $this->logError($exception);
        }
    }

    /**
     * @param  Exception  $exception
     * @return void
     */
    public function logError(Exception $exception): void
    {
        logToFile('error', sprintf('Error: %s, File: %s, Line: %s', $exception->getMessage(), $exception->getFile(), $exception->getLine()));
    }
}
