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
        Schema::create('page_block', function (Blueprint $table) {
            $table->integer('sequence');
            $table->text('additional_data');
            
            $table->foreignId('page_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('block_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_block');
    }
};
