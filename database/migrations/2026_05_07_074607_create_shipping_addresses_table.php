<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('shippings', function (Blueprint $table) {
        $table->id();

        $table->foreignId('order_id')->constrained()->cascadeOnDelete();

        $table->string('full_name');
        $table->string('phone');
        $table->string('address');
        $table->string('city');
        $table->string('country');

        $table->string('carrier')->nullable(); // DHL, Aramex...
        $table->string('tracking_number')->nullable();

        $table->enum('status', [
            'pending',
            'packed',
            'shipped',
            'in_transit',
            'delivered',
        ])->default('pending');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings'); // ✅ صح
    }
};
