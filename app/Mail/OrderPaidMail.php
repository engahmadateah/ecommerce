<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Sent to the customer once their payment is confirmed. */
class OrderPaidMail extends Mailable
{
    public function __construct(public Order $order)
    {
        $this->order->loadMissing(['items', 'user', 'shipping']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Order #'.$this->order->id.' confirmed — thank you!');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.order-paid');
    }
}
