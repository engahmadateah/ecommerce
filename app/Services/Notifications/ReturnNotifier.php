<?php

namespace App\Services\Notifications;

use App\Mail\ReturnRequestedAdminMail;
use App\Mail\ReturnUpdatedMail;
use App\Models\OrderReturn;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Throwable;

/** E-mails for returns. A mail failure must never break a refund, so it is only reported. */
class ReturnNotifier
{
    public function requested(OrderReturn $return): void
    {
        $address = config('mail.admin_address') ?: Setting::query()->value('email');

        if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
            $this->safely(fn () => Mail::to($address)->send(new ReturnRequestedAdminMail($return)));
        }
    }

    public function updated(OrderReturn $return): void
    {
        $return->loadMissing('order.user', 'order.shipping');

        if ($email = $return->order->customerEmail()) {
            $this->safely(fn () => Mail::to($email)->send(new ReturnUpdatedMail($return)));
        }
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
