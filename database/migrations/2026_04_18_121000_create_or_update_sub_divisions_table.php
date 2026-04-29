<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sub_divisions')) {
            Schema::create('sub_divisions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('division_id')->constrained('divisions');
                $table->string('name');
                $table->string('subdivision_code', 50)->nullable();
                $table->string('colony_name')->nullable();
                $table->string('locality_address')->nullable();
                $table->boolean('status')->default(true);
                $table->softDeletes();
                $table->timestamps();
            });

            return;
        }

        Schema::table('sub_divisions', function (Blueprint $table) {
            if (! Schema::hasColumn('sub_divisions', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }

            if (! Schema::hasColumn('sub_divisions', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('sub_divisions')) {
            return;
        }

        Schema::table('sub_divisions', function (Blueprint $table) {
            if (Schema::hasColumn('sub_divisions', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
