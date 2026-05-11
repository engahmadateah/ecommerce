<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('title')->nullable()->after('code');
            $table->text('description')->nullable()->after('title');
            $table->text('how_to_use')->nullable()->after('description');
            $table->string('image')->nullable()->after('how_to_use');
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['title', 'description', 'how_to_use', 'image']);
        });
    }
};