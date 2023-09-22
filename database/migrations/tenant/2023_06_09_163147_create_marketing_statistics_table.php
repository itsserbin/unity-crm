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
        Schema::create('marketing_statistics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('source_id')
                ->nullable()
                ->references('id')
                ->on('marketing_sources')
                ->onDelete('cascade');

            $table->decimal('count_orders')->default(0);
            $table->decimal('average_order_items')->default(0);

            $table->decimal('sum_per_order')->default(0);
            $table->decimal('sum_per_approve_order')->default(0);
            $table->decimal('sum_per_success_order')->default(0);

            $table->decimal('average_order_sale_price')->default(0);
            $table->decimal('average_order_trade_price')->default(0);
            $table->decimal('average_order_clear_price')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_statistics');
    }
};
