<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Sent to the customer when the order is marked as shipped. */
class OrderShippedMail extends Mailable
{
    public function __construct(public Order $order)
    {
        $this->order->loadMissing(['items', 'user', 'shipping']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your order #'.$this->order->id.' is on its way');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.order-shipped');
    }
}
