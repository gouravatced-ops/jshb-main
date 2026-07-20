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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE notifications MODIFY COLUMN notification_type ENUM('application_created', 'application_forwarded', 'application_approved', 'application_rejected', 'application_send_back', 'application_completed', 'document_uploaded', 'note_added', 'reminder', 'status_change', 'document_request')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE notifications MODIFY COLUMN notification_type ENUM('application_created', 'application_forwarded', 'application_approved', 'application_rejected', 'application_send_back', 'application_completed', 'document_uploaded', 'note_added', 'reminder', 'status_change')");
    }
};
