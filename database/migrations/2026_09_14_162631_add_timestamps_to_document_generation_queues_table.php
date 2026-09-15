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
        Schema::table('document_generation_queues', function (Blueprint $table) {
            $table->timestamp('queued_at')->nullable()->after('error_message');
            $table->timestamp('completed_at')->nullable()->after('queued_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_generation_queues', function (Blueprint $table) {
            $table->dropColumn(['queued_at', 'completed_at']);
        });
    }
};
