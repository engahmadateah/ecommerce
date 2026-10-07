<?php

namespace App\Mail;

use App\Models\OrderReturn;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Tells the customer what happened to their return (approved / rejected / refunded). */
class ReturnUpdatedMail extends Mailable
{
    public function __construct(public OrderReturn $return)
    {
        $this->return->loadMissing('order.user', 'order.shipping');
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Update on your return for order #'.$this->return->order_id);
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.return-updated');
    }
}
