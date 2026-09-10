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
        Schema::table('application_notes', function (Blueprint $table) {
            $table->boolean('otp_verified')->default(0)->after('remarks');
        });

        Schema::table('application_correspondences', function (Blueprint $table) {
            $table->boolean('otp_verified')->default(0)->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_notes', function (Blueprint $table) {
            $table->dropColumn('otp_verified');
        });

        Schema::table('application_correspondences', function (Blueprint $table) {
            $table->dropColumn('otp_verified');
        });
    }
};
