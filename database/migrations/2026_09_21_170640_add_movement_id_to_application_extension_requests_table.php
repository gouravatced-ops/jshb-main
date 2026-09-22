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
        Schema::table('application_extension_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('movement_id')->nullable()->after('application_id');
            $table->foreign('movement_id')->references('id')->on('application_movements')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_extension_requests', function (Blueprint $table) {
            $table->dropForeign(['movement_id']);
            $table->dropColumn('movement_id');
        });
    }
};
