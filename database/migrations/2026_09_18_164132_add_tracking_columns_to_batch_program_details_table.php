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
        Schema::table('batch_program_details', function (Blueprint $table) {
            $table->string('recipient_email')->nullable()->after('application_id');
            $table->longText('mail_body')->nullable()->after('error_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_program_details', function (Blueprint $table) {
            $table->dropColumn(['recipient_email', 'mail_body']);
        });
    }
};
