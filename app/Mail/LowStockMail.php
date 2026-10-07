<?php

namespace App\Mail;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Daily list of products that are running out. */
class LowStockMail extends Mailable
{
    public function __construct(public Collection $products, public int $threshold)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->products->count().' product(s) running low on stock');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.low-stock');
    }
}
