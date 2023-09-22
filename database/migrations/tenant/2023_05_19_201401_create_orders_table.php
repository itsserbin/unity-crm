<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id');
            $table->foreignId('status_id');
            $table->foreignId('client_id')->nullable();
            $table->foreignId('manager_id')->nullable();
            $table->foreignId('delivery_service_id')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('tracking_code')->nullable();
            $table->text('manager_comment')->nullable();
            $table->text('client_comment')->nullable();

            $table->decimal('total_price')->nullable();
            $table->decimal('trade_price')->nullable();
            $table->decimal('clear_total_price')->nullable();
            $table->decimal('discount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
