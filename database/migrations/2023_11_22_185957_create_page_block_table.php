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
            $table->id();
            $table->integer('sequence');
            $table->text('additional_data');
            
            $table->unsignedBigInteger('page_id');
            $table
                ->foreign('page_id')
                ->references('id')->on('pages')
                ->onDelete('cascade');

            $table->unsignedBigInteger('block_id');
            $table
                ->foreign('block_id')
                ->references('id')->on('blocks')
                ->onDelete('cascade');

            $table->timestamps();
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
