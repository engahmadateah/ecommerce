<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->string('sku', 64)->nullable()->after('slug');
            $table->boolean('is_published')->default(true)->after('stock');
            $table->json('gallery')->nullable()->after('image');
        });

        // Give every existing product a unique, readable slug.
        $used = [];
        foreach (DB::table('products')->orderBy('id')->get(['id', 'name']) as $row) {
            $base = Str::slug((string) $row->name) ?: 'product';
            $slug = $base;
            $i = 2;
            while (isset($used[$slug])) {
                $slug = $base.'-'.$i++;
            }
            $used[$slug] = true;
            DB::table('products')->where('id', $row->id)->update(['slug' => $slug]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->unique('slug');
            $table->unique('sku');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropUnique(['sku']);
            $table->dropIndex(['is_published']);
            $table->dropColumn(['slug', 'sku', 'is_published', 'gallery']);
        });
    }
};
