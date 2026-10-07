<?php

namespace App\Console\Commands;

use App\Services\Cart\AbandonedCartService;
use Illuminate\Console\Command;

class SendAbandonedCartReminders extends Command
{
    protected $signature = 'carts:remind';

    protected $description = 'E-mail customers who left items in their cart (one reminder per cart)';

    public function handle(AbandonedCartService $carts): int
    {
        $this->info('Reminders sent: '.$carts->sendDue());

        return self::SUCCESS;
    }
}
