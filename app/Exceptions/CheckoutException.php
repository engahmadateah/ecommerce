<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * A checkout problem whose message is safe to show to the customer.
 */
class CheckoutException extends RuntimeException
{
    public static function emptyCart(): self
    {
        return new self('Your cart is empty.');
    }

    public static function cartChanged(): self
    {
        return new self('Prices or availability changed while you were shopping. Please review your cart and try again.');
    }

    public static function outOfStock(string $name): self
    {
        return new self("\"{$name}\" is out of stock or not available in the requested quantity.");
    }

    public static function optionRequired(): self
    {
        return new self('Please choose an option (size, colour, ...) first.');
    }

    public static function coupon(string $reason): self
    {
        return new self($reason);
    }

    public static function emailRequired(): self
    {
        return new self('Please enter your e-mail address.');
    }

    public static function payment(): self
    {
        return new self('We could not start the payment. Please try again in a moment.');
    }
}
