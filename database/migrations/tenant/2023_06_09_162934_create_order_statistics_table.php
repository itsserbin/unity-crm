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
        Schema::create('order_statistics', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('count')->default(0);

            $table->foreignId('status_id')
                ->references('id')
                ->on('statuses')
                ->onDelete('cascade');

            $table->string('group_slug');
            $table->foreign('group_slug')
                ->references('slug')
                ->on('status_groups')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_statistics');
    }
};
