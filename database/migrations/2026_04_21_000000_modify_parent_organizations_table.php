<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parent_organizations', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->string('display_code')->nullable()->unique()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('parent_organizations', function (Blueprint $table) {
            $table->dropColumn('display_code');
            $table->string('state')->nullable();
        });
    }
};
