<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Connection name for allottees database.
     */
    protected $connection = 'adms_allottees';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('notifications')) {
            Schema::connection($this->connection)->create('notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('application_id')->nullable()->index();
                $table->unsignedBigInteger('movement_id')->nullable();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('notification_type', 100)->default('info');
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
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('notifications');
    }
};
