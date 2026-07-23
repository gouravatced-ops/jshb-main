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
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('allottee_id');
            $table->unsignedBigInteger('document_master_id');
            $table->unsignedBigInteger('requested_by'); // engineer ID
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'uploaded', 'expired'])->default('pending');
            $table->dateTime('expires_at');
            $table->unsignedBigInteger('uploaded_document_id')->nullable(); // links to adms_allottees.allottee_documents
            $table->timestamps();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean('is_whatsapp_sent')->default(0)->after('is_sms_sent');
            $table->dateTime('whatsapp_sent_at')->nullable()->after('is_whatsapp_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['is_whatsapp_sent', 'whatsapp_sent_at']);
        });
    }
};
