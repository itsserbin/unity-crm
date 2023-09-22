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
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')
                ->nullable()
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');

            $table->string('month');
            $table->decimal('start_month_balance');
            $table->decimal('end_month_balance');
            $table->decimal('difference');
            $table->decimal('approved_income');
            $table->decimal('approved_consumption');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};
