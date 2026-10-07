<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite (used by the test suite) can't add foreign keys to an existing table.
        $withForeignKeys = DB::getDriverName() !== 'sqlite';

        Schema::table('orders', function (Blueprint $table) use ($withForeignKeys) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('total_price');
            $table->decimal('discount_total', 10, 2)->default(0)->after('subtotal');
            $table->unsignedBigInteger('coupon_id')->nullable()->after('discount_total');

            // One Stripe session can only ever belong to one order.
            $table->string('stripe_session_id')->nullable()->unique()->after('status');
            $table->string('payment_intent_id')->nullable()->after('stripe_session_id');
            $table->timestamp('paid_at')->nullable()->after('payment_intent_id');
            $table->timestamp('expires_at')->nullable()->after('paid_at');

            $table->index(['status', 'expires_at']);

            if ($withForeignKeys) {
                $table->foreign('coupon_id')->references('id')->on('coupons')->nullOnDelete();
            }
        });

        // Existing orders were charged the amount in total_price with no discount breakdown.
        DB::table('orders')->update(['subtotal' => DB::raw('total_price')]);
    }

    public function down(): void
    {
        $withForeignKeys = DB::getDriverName() !== 'sqlite';

        Schema::table('orders', function (Blueprint $table) use ($withForeignKeys) {
            if ($withForeignKeys) {
                $table->dropForeign(['coupon_id']);
            }

            $table->dropIndex(['status', 'expires_at']);
            $table->dropUnique(['stripe_session_id']);
            $table->dropColumn([
                'subtotal',
                'discount_total',
                'coupon_id',
                'stripe_session_id',
                'payment_intent_id',
                'paid_at',
                'expires_at',
            ]);
        });
    }
};
