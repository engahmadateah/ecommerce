<?php

namespace App\Services\Notifications;

use App\Mail\NewOrderAdminMail;
use App\Mail\OrderPaidMail;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Sends the order e-mails. A mail problem (SMTP down, bad address) must never
 * break checkout or an admin action, so every send is wrapped and only reported.
 */
class OrderNotifier
{
    public function paid(Order $order): void
    {
        $order->loadMissing(['user', 'shipping']);

        if ($email = $order->customerEmail()) {
            $this->safely(fn () => Mail::to($email)->send(new OrderPaidMail($order)));
        }

        if ($admin = $this->adminAddress()) {
            $this->safely(fn () => Mail::to($admin)->send(new NewOrderAdminMail($order)));
        }
    }

    public function shipped(Order $order): void
    {
        $order->loadMissing(['user', 'shipping']);

        if ($email = $order->customerEmail()) {
            $this->safely(fn () => Mail::to($email)->send(new OrderShippedMail($order)));
        }
    }

    private function adminAddress(): ?string
    {
        $address = config('mail.admin_address') ?: Setting::query()->value('email');

        return filter_var($address, FILTER_VALIDATE_EMAIL) ? $address : null;
    }

    private function safely(callable $send): void
    {
        try {
            $send();
        } catch (Throwable $e) {
            report($e);
        }
    }
}
