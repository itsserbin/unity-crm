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
        Schema::create('tracking_code_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->nullable()
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->string('code');
            $table->string('title');
            $table->string('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_code_statuses');
    }
};
