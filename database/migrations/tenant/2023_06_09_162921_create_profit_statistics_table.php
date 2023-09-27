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
        Schema::create('profit_statistics', function (Blueprint $table) {
            $table->id();
            $table->date('date');

            $table->foreignId('status_id')
                ->references('id')
                ->on('statuses')
                ->onDelete('cascade');

            $table->foreignId('source_id')
                ->references('id')
                ->on('sources')
                ->onDelete('cascade');

            $table->string('group_slug');
            $table->foreign('group_slug')
                ->references('slug')
                ->on('status_groups')
                ->onDelete('cascade');

            $table->decimal('orders_cost')->default(0);
            $table->decimal('orders_sale_price')->default(0);
            $table->decimal('orders_clear_price')->default(0);
            $table->decimal('orders_trade_price')->default(0);

            $table->integer('total_additional_sales')->default(0);
            $table->decimal('total_additional_sales_items_sale_price')->default(0);
            $table->decimal('total_additional_sales_items_trade_price_price')->default(0);
            $table->decimal('total_additional_sales_items_trade_clear_price')->default(0);

            $table->integer('total_prepayment')->default(0);
            $table->decimal('total_prepayment_sum')->default(0);

            $table->integer('total_sales_of_air')->default(0);
            $table->decimal('total_sales_of_air_sum')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_statistics');
    }
};
