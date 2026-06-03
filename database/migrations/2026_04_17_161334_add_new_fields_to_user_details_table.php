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
        Schema::table('user_details', function (Blueprint $table) {
            $table->date('anniversary_date')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('spouse_name')->nullable();
            $table->integer('no_of_children')->nullable();
            $table->integer('boys')->nullable();
            $table->integer('girls')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn(['anniversary_date', 'date_of_birth', 'spouse_name', 'no_of_children', 'boys', 'girls']);
        });
    }
};
