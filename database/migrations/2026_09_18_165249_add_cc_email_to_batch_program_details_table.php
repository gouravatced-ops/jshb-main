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
            $table->string('cc_email')->nullable()->after('recipient_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_program_details', function (Blueprint $table) {
            $table->dropColumn('cc_email');
        });
    }
};
