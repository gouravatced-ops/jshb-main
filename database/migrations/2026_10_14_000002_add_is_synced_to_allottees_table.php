<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add is_synced column to adms_allottees connection
        if (Schema::connection('adms_allottees')->hasTable('allottees')) {
            Schema::connection('adms_allottees')->table('allottees', function (Blueprint $table) {
                if (!Schema::connection('adms_allottees')->hasColumn('allottees', 'is_synced')) {
                    $table->tinyInteger('is_synced')
                        ->default(0)
                        ->comment('0: Manual, 1: Synced from Citizen Portal')
                        ->after('is_step_completed');
                }
            });

            // Backfill already synced records if future_allottees table has synced records
            if (Schema::connection('adms_allottees')->hasTable('future_allottees')) {
                $syncedAllotteeIds = DB::connection('adms_allottees')->table('future_allottees')
                    ->whereNotNull('synced_allottee_id')
                    ->pluck('synced_allottee_id')
                    ->filter()
                    ->all();

                if (!empty($syncedAllotteeIds)) {
                    DB::connection('adms_allottees')->table('allottees')
                        ->whereIn('id', $syncedAllotteeIds)
                        ->update(['is_synced' => 1]);
                }
            }
        }

        // 2. Add is_synced column to default connection if allottees table exists there as well
        $defaultConn = config('database.default');
        if ($defaultConn !== 'adms_allottees' && Schema::connection($defaultConn)->hasTable('allottees')) {
            Schema::connection($defaultConn)->table('allottees', function (Blueprint $table) use ($defaultConn) {
                if (!Schema::connection($defaultConn)->hasColumn('allottees', 'is_synced')) {
                    $table->tinyInteger('is_synced')
                        ->default(0)
                        ->comment('0: Manual, 1: Synced from Citizen Portal')
                        ->after('is_step_completed');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection('adms_allottees')->hasTable('allottees')) {
            Schema::connection('adms_allottees')->table('allottees', function (Blueprint $table) {
                if (Schema::connection('adms_allottees')->hasColumn('allottees', 'is_synced')) {
                    $table->dropColumn('is_synced');
                }
            });
        }

        $defaultConn = config('database.default');
        if ($defaultConn !== 'adms_allottees' && Schema::connection($defaultConn)->hasTable('allottees')) {
            Schema::connection($defaultConn)->table('allottees', function (Blueprint $table) use ($defaultConn) {
                if (Schema::connection($defaultConn)->hasColumn('allottees', 'is_synced')) {
                    $table->dropColumn('is_synced');
                }
            });
        }
    }
};
