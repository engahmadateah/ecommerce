<?php

namespace App\Mail;

use App\Models\OrderReturn;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Tells the shop owner a customer asked to return something. */
class ReturnRequestedAdminMail extends Mailable
{
    public function __construct(public OrderReturn $return)
    {
        $this->return->loadMissing('order');
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Return request for order #'.$this->return->order_id);
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.return-requested-admin');
    }
}
