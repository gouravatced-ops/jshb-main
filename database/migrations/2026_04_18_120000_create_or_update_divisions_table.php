<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('divisions')) {
            Schema::create('divisions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('division_code', 50)->nullable();
                $table->boolean('status')->default(true);
                $table->softDeletes();
                $table->timestamps();
            });

            return;
        }

        Schema::table('divisions', function (Blueprint $table) {
            if (! Schema::hasColumn('divisions', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('divisions')) {
            return;
        }

        Schema::table('divisions', function (Blueprint $table) {
            if (Schema::hasColumn('divisions', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
