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
        Schema::table('communication_settings', function (Blueprint $table) {
            if (Schema::hasColumn('communication_settings', 'user_type')) {
                $table->dropColumn('user_type');
            }
            if (!Schema::hasColumn('communication_settings', 'role_id')) {
                $table->foreignId('role_id')->after('id')->constrained('roles')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('communication_settings', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->string('user_type')->nullable();
        });
    }
};
