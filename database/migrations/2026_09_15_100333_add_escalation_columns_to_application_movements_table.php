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
        Schema::table('application_movements', function (Blueprint $table) {
            $table->boolean('is_escalated')->default(false)->after('status');
            $table->dateTime('escalated_at')->nullable()->after('is_escalated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_movements', function (Blueprint $table) {
            $table->dropColumn(['is_escalated', 'escalated_at']);
        });
    }
};
