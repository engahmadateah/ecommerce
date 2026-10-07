<?php

namespace App\Console\Commands;

use App\Mail\LowStockMail;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendLowStockAlert extends Command
{
    protected $signature = 'stock:alert';

    protected $description = 'E-mail the shop owner the list of products that are low on stock';

    public function handle(): int
    {
        $threshold = (int) config('shop.low_stock_threshold', 5);

        $products = Product::query()
            ->where('is_published', true)
            ->where('stock', '<=', $threshold)
            ->orderBy('stock')
            ->get();

        if ($products->isEmpty()) {
            $this->info('Nothing is low on stock.');

            return self::SUCCESS;
        }

        $address = config('mail.admin_address') ?: Setting::query()->value('email');

        if (! filter_var($address, FILTER_VALIDATE_EMAIL)) {
            $this->warn('No admin e-mail configured (MAIL_ADMIN_ADDRESS), alert not sent.');

            return self::SUCCESS;
        }

        Mail::to($address)->send(new LowStockMail($products, $threshold));
        $this->info("Alert sent for {$products->count()} product(s).");

        return self::SUCCESS;
    }
}
