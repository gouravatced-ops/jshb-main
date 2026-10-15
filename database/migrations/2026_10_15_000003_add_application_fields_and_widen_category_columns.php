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
        // 1. Widen category columns on allottees table to prevent truncation
        if (Schema::connection('adms_allottees')->hasTable('allottees')) {
            Schema::connection('adms_allottees')->table('allottees', function (Blueprint $table) {
                $table->string('allottee_category', 100)->nullable()->change();
                $table->string('allottee_category_hindi', 150)->nullable()->change();
            });
        }

        // 2. Add application tracking fields to future_allottees table if not present
        if (Schema::connection('adms_allottees')->hasTable('future_allottees')) {
            Schema::connection('adms_allottees')->table('future_allottees', function (Blueprint $table) {
                if (!Schema::connection('adms_allottees')->hasColumn('future_allottees', 'application_no')) {
                    $table->string('application_no', 100)->nullable()->after('reference_portal_id');
                }
                if (!Schema::connection('adms_allottees')->hasColumn('future_allottees', 'application_day')) {
                    $table->integer('application_day')->nullable()->after('application_no');
                }
                if (!Schema::connection('adms_allottees')->hasColumn('future_allottees', 'application_month')) {
                    $table->integer('application_month')->nullable()->after('application_day');
                }
                if (!Schema::connection('adms_allottees')->hasColumn('future_allottees', 'application_year')) {
                    $table->integer('application_year')->nullable()->after('application_month');
                }
                $table->string('allottee_category', 100)->nullable()->change();
                $table->string('allottee_category_hindi', 150)->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection('adms_allottees')->hasTable('future_allottees')) {
            Schema::connection('adms_allottees')->table('future_allottees', function (Blueprint $table) {
                if (Schema::connection('adms_allottees')->hasColumn('future_allottees', 'application_no')) {
                    $table->dropColumn(['application_no', 'application_day', 'application_month', 'application_year']);
                }
            });
        }
    }
};
