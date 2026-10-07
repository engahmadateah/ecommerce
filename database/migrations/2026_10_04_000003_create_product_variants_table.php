<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');                                   // e.g. "Large / Red"
            $table->string('sku', 64)->nullable()->unique();
            $table->decimal('price', 10, 2)->nullable();              // null = same as the product
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id')->nullable()->after('package_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['variant_id']);
            $table->dropColumn('variant_id');
        });

        Schema::dropIfExists('product_variants');
    }
};
