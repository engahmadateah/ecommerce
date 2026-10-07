<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['products', 'categories'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->text('translations')->nullable(); // {"ar": {"name": "...", "description": "..."}}
            });
        }
    }

    public function down(): void
    {
        foreach (['products', 'categories'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('translations');
            });
        }
    }
};
