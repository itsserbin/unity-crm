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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreignId('product_id')->nullable();
            $table->foreignId('preview_id')->nullable();

            $table->boolean('additional_sale')->default(0);
            $table->string('sku')->nullable();
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->integer('count')->default(1);
            $table->decimal('trade_price')->nullable();
            $table->decimal('product_price')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('sale_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
