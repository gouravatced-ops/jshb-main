<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('block_lists')) {
            Schema::create('block_lists', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('district_id');
                $table->string('block_name_eng');
                $table->string('block_name_hn')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->softDeletes();
            });

            return;
        }

        Schema::table('block_lists', function (Blueprint $table) {
            if (! Schema::hasColumn('block_lists', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('block_lists', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }

            if (! Schema::hasColumn('block_lists', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('block_lists')) {
            return;
        }

        Schema::table('block_lists', function (Blueprint $table) {
            if (Schema::hasColumn('block_lists', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
