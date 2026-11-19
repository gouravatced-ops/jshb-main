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
        Schema::table('workflow_steps', function (Blueprint $table) {
            if (!Schema::hasColumn('workflow_steps', 'can_approve')) {
                $table->boolean('can_approve')->default(0)->after('can_send_back');
            }
            if (!Schema::hasColumn('workflow_steps', 'can_verify_site')) {
                $table->boolean('can_verify_site')->default(0)->after('can_approve');
            }
            if (!Schema::hasColumn('workflow_steps', 'can_correspondence')) {
                $table->boolean('can_correspondence')->default(0)->after('can_verify_site');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_steps', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('workflow_steps', 'can_approve')) {
                $columnsToDrop[] = 'can_approve';
            }
            if (Schema::hasColumn('workflow_steps', 'can_verify_site')) {
                $columnsToDrop[] = 'can_verify_site';
            }
            if (Schema::hasColumn('workflow_steps', 'can_correspondence')) {
                $columnsToDrop[] = 'can_correspondence';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
