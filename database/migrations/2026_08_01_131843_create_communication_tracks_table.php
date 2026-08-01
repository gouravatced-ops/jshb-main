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
        Schema::create('communication_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id')->nullable()->comment('To track communication against an application');
            $table->unsignedBigInteger('allottee_id')->nullable()->comment('To track communication against an allottee');
            $table->string('sender_type')->comment('system, jshb_user, allottee');
            $table->unsignedBigInteger('sender_id')->nullable()->comment('ID of the sender, null if system');
            $table->string('receiver_type')->comment('jshb_user, allottee, role');
            $table->unsignedBigInteger('receiver_id')->nullable()->comment('ID of the receiver user or allottee');
            $table->unsignedBigInteger('role_id')->nullable()->comment('ID of the receiver role if applicable');
            $table->string('communication_type')->comment('email, sms, whatsapp');
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('browser_agent')->nullable();
            $table->string('status')->default('success')->comment('success or failed');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_tracks');
    }
};
