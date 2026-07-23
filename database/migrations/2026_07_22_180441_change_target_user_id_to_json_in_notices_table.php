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
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn('target_user_id');
        });

        Schema::table('notices', function (Blueprint $table) {
            $table->text('target_user_id')->nullable()->after('target_division_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn('target_user_id');
        });

        Schema::table('notices', function (Blueprint $table) {
            $table->unsignedBigInteger('target_user_id')->nullable()->after('target_division_id');
        });
    }
};
