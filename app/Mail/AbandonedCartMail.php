<?php

namespace App\Mail;

use App\Models\AbandonedCart;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** One friendly reminder about the items left in the cart. */
class AbandonedCartMail extends Mailable
{
    public function __construct(public AbandonedCart $cart)
    {
        $this->cart->loadMissing('user');
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'You left something in your cart');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.abandoned-cart');
    }
}
