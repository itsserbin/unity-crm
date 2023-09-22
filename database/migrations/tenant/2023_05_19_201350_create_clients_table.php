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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->json('phones');
            $table->json('emails');
            $table->string('full_name')->nullable();
            $table->text('comment')->nullable();

            $table->integer('number_of_orders')->default(0);
            $table->integer('number_of_success_orders')->default(0);

            $table->integer('success_average_check')->default(0);
            $table->decimal('average_check')->default(0);

            $table->integer('success_general_check')->default(0);
            $table->decimal('general_check')->default(0);

            $table->dateTime('last_order_created_at')->nullable();
            $table->dateTime('success_last_order_created_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
