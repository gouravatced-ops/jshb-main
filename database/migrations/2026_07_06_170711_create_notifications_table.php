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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id')->nullable()->index();
            $table->unsignedBigInteger('movement_id')->nullable();
            $table->unsignedBigInteger('user_id')->index();
            $table->enum('notification_type', [
                'application_created',
                'application_forwarded',
                'application_approved',
                'application_rejected',
                'application_send_back',
                'application_completed',
                'document_uploaded',
                'note_added',
                'reminder',
                'status_change'
            ]);
            $table->string('subject', 255);
            $table->text('message');
            $table->string('link', 500)->nullable();
            $table->boolean('is_read')->default(0)->index();
            $table->dateTime('read_at')->nullable();
            $table->boolean('is_email_sent')->default(0);
            $table->dateTime('email_sent_at')->nullable();
            $table->boolean('is_sms_sent')->default(0);
            $table->dateTime('sms_sent_at')->nullable();
            $table->boolean('is_push_sent')->default(0);
            $table->dateTime('push_sent_at')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
            
            $table->foreign('application_id', 'fk_notifications_application')
                  ->references('id')
                  ->on('applications')
                  ->onDelete('cascade');
                  
            $table->foreign('movement_id', 'fk_notifications_movement')
                  ->references('id')
                  ->on('application_movements')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
