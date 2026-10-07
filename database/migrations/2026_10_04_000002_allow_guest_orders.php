<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('guest_email')->nullable()->after('user_id');
            $table->index('guest_email');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['guest_email']);
            $table->dropColumn('guest_email');
        });
    }
};
