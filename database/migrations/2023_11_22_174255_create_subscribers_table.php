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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->json('extra_attributes')->nullable();
            $table->string('status')->default('pending');

            $table->unsignedBigInteger('subscription_id');
            $table
                ->foreign('subscription_id')
                ->references('id')->on('subscriptions')
                ->onDelete('cascade');

            $table
                ->unique(['subscription_id', 'email']);

            $table->index(['subscription_id', 'subscribed_at', 'unsubscribed_at'], 'email_list_subscribed_index');

            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
