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
        Schema::connection('adms_allottees')->table('allottees', function (Blueprint $table) {
            $table->boolean('is_cancelled')->default(false)->after('is_step_completed');
            $table->text('cancellation_reason')->nullable()->after('is_cancelled');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('adms_allottees')->table('allottees', function (Blueprint $table) {
            $table->dropColumn(['is_cancelled', 'cancellation_reason', 'cancelled_at']);
        });
    }
};
