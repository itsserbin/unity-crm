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
        Schema::create('bank_account_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');

            $table->string('movement_id')->nullable();
            $table->dateTime('date');
            $table->decimal('sum');
            $table->decimal('balance')->nullable();
            $table->foreignId('category_id')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_account_movements');
    }
};
