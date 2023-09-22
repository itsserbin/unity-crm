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
        Schema::create('marketing_source_costs', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->foreignId('source_id')
                ->references('id')
                ->on('marketing_sources')
                ->onDelete('cascade');
            $table->decimal('costs')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_source_costs');
    }
};
