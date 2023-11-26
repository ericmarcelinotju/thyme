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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('default_from_email')->nullable();
            $table->string('default_from_name')->nullable();

            $table->string('redirect_after_unsubscribed')->nullable();

            $table->boolean('requires_confirmation')->default(false);
            $table->string('confirmation_mail_subject')->nullable();
            $table->text('confirmation_mail_content')->nullable();

            $table->boolean('send_welcome_mail')->default(false);
            $table->string('welcome_mail_subject')->nullable();
            $table->text('welcome_mail_content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
