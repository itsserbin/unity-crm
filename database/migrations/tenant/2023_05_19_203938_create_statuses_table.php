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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('group_slug');
            $table->foreign('group_slug')
                ->references('slug')
                ->on('status_groups')
                ->onDelete('cascade');

            $table->string('title');
            $table->integer('orders_count')->default(0);
            $table->boolean('is_system_status')->default(0);
            $table->boolean('published')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
