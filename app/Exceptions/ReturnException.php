<?php

namespace App\Exceptions;

use RuntimeException;

/** A return/refund action that is not allowed. The message is safe to show to the user. */
class ReturnException extends RuntimeException
{
}
