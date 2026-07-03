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
            // Drop old columns
            $table->dropColumn([
                'organization',
                'date_of_birth',
                'anniversary_date',
                'spouse_name',
                'no_of_children',
                'boys',
                'girls'
            ]);

            // Add new optional date columns
            $table->date('date_of_joining')->nullable();
            $table->date('date_of_retirement')->nullable();
            $table->date('date_of_contractual')->nullable();
            $table->date('date_of_deputation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_joining',
                'date_of_retirement',
                'date_of_contractual',
                'date_of_deputation'
            ]);

            $table->string('organization')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('anniversary_date')->nullable();
            $table->string('spouse_name')->nullable();
            $table->integer('no_of_children')->nullable();
            $table->integer('boys')->nullable();
            $table->integer('girls')->nullable();
        });
    }
};
