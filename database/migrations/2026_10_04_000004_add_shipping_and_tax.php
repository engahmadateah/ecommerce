<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Shop-wide rules (editable in admin Settings). NULL = use config/shop.php defaults.
        Schema::table('settings', function (Blueprint $table) {
            $table->decimal('shipping_fee', 10, 2)->nullable();
            $table->decimal('free_shipping_threshold', 10, 2)->nullable();
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->boolean('tax_included')->nullable();
            $table->string('tax_label', 30)->nullable();
        });

        // What each order was actually charged (history must never change when rules do).
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('shipping_total', 10, 2)->default(0);
            $table->decimal('tax_total', 10, 2)->default(0);
            $table->boolean('tax_included')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_total', 'tax_total', 'tax_included']);
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['shipping_fee', 'free_shipping_threshold', 'tax_rate', 'tax_included', 'tax_label']);
        });
    }
};
