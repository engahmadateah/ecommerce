<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * order_items.product_id was NOT NULL, so buying a bundle (which has no product)
 * crashed after the customer had already paid. Items now snapshot their name
 * and may point at a package instead of a product. Deleting a product no
 * longer erases the order history either (nullOnDelete instead of cascade).
 */
return new class extends Migration
{
    public function up(): void
    {
        // SQLite (used by the test suite) can't drop/add foreign keys on an existing table.
        $withForeignKeys = DB::getDriverName() !== 'sqlite';

        if ($withForeignKeys) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
            });
        }

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
        });

        Schema::table('order_items', function (Blueprint $table) use ($withForeignKeys) {
            $table->unsignedBigInteger('package_id')->nullable()->after('product_id')->index();
            $table->string('name')->nullable()->after('package_id');

            if ($withForeignKeys) {
                $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
                $table->foreign('package_id')->references('id')->on('packages')->nullOnDelete();
            }
        });

        DB::statement(
            'UPDATE order_items SET name = (SELECT products.name FROM products WHERE products.id = order_items.product_id)'
        );
    }

    public function down(): void
    {
        // product_id stays nullable: making it NOT NULL again would fail (or delete)
        // for any bundle order created since.
        $withForeignKeys = DB::getDriverName() !== 'sqlite';

        Schema::table('order_items', function (Blueprint $table) use ($withForeignKeys) {
            if ($withForeignKeys) {
                $table->dropForeign(['package_id']);
            }

            $table->dropIndex(['package_id']);
            $table->dropColumn(['package_id', 'name']);
        });
    }
};
