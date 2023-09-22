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
        Schema::create('profit_and_losses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')
                ->nullable()
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');

            $table->string('month');
            $table->decimal('purchase_cost');
            $table->decimal('business_profitability');
            $table->decimal('total_revenues');
            $table->decimal('net_profit');
            $table->decimal('costs');
            $table->decimal('investments');
            $table->decimal('returned_investments');
            $table->decimal('dividends');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_and_losses');
    }
};
