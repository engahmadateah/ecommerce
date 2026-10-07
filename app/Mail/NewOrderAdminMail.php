<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Sent to the shop owner when a new paid order arrives. */
class NewOrderAdminMail extends Mailable
{
    public function __construct(public Order $order)
    {
        $this->order->loadMissing(['items', 'user', 'shipping']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New order #'.$this->order->id.' — $'.number_format((float) $this->order->total_price, 2));
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.new-order-admin');
    }
}
