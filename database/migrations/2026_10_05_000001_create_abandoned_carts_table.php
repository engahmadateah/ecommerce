<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('token', 40)->unique();
            $table->json('items');                       // [{key, name, price, quantity}]
            $table->timestamp('last_activity_at')->index();
            $table->timestamp('reminded_at')->nullable();
            $table->boolean('opted_out')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abandoned_carts');
    }
};
