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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('public_token', 40)->nullable();
        });

        // A secret, unguessable link per order (used in e-mails to track the order / open the invoice).
        foreach (DB::table('orders')->pluck('id') as $id) {
            DB::table('orders')->where('id', $id)->update(['public_token' => Str::random(40)]);
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->unique('public_token');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['public_token']);
            $table->dropColumn('public_token');
        });
    }
};
