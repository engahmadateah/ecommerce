<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('requested'); // requested | approved | rejected | refunded
            $table->string('reason', 60);
            $table->text('details')->nullable();
            $table->json('items');                              // [{order_item_id, quantity}]
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->boolean('restocked')->default(false);
            $table->text('admin_note')->nullable();
            $table->string('refund_id')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('refunded_total', 10, 2)->default(0)->after('total_price');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('refunded_total');
        });

        Schema::dropIfExists('order_returns');
    }
};
