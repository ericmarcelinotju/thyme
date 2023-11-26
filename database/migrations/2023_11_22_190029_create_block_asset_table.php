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
        Schema::create('block_asset', function (Blueprint $table) {
            $table->id();
            $table->integer('sequence');
            $table->timestamps();
            
            
            $table->unsignedBigInteger('asset_id');
            $table
                ->foreign('asset_id')
                ->references('id')->on('assets')
                ->onDelete('cascade');
                
            $table->unsignedBigInteger('block_id');
            $table
                ->foreign('block_id')
                ->references('id')->on('blocks')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_asset');
    }
};
