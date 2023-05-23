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
        Schema::create('delivery_services', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(1);
            $table->string('title');
            $table->string('type')->nullable();
            $table->string('api_key')->nullable();
            $table->json('configuration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_services');
    }
};
