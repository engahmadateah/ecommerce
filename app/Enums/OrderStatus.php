<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';       // stock reserved, waiting for payment
    case Paid = 'paid';             // payment confirmed
    case Shipped = 'shipped';
    case Completed = 'completed';
    case Cancelled = 'cancelled';   // payment abandoned/expired, stock released
    case Refunded = 'refunded';     // the whole amount was given back
}
