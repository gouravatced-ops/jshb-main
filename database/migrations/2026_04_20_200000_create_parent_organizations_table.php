<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('pin_code', 20)->nullable();
            $table->string('state')->nullable();
            $table->string('district')->nullable();
            $table->string('locality')->nullable();
            $table->string('police_station')->nullable();
            $table->string('post_office')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['parent_organization_id']);
            $table->foreign('parent_organization_id')
                ->nullable()
                ->references('id')
                ->on('parent_organizations')
                ->nullOnDelete();
            $table->boolean('district_wise_posting')->default(false)->after('parent_organization_id');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['parent_organization_id']);
            $table->dropColumn('district_wise_posting');
            $table->foreign('parent_organization_id')
                ->nullable()
                ->references('id')
                ->on('organizations')
                ->nullOnDelete();
        });

        Schema::dropIfExists('parent_organizations');
    }
};
