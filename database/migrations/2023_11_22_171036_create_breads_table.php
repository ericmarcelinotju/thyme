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
        Schema::create('breads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('table_name');
            $table->json('columns');

            $table->boolean('is_allow_browse');
            $table->boolean('is_allow_read');
            $table->boolean('is_allow_edit');
            $table->boolean('is_allow_add');
            $table->boolean('is_allow_delete');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breads');
    }
};
