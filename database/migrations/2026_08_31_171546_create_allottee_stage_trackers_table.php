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
        Schema::create('allottee_stage_trackers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allottee_id');
            $table->string('application_no')->nullable();
            $table->string('stage_type'); // e.g., 'allotment_letter', 'possession_letter'
            $table->string('status')->default('completed'); // 'generated', 'approved', 'completed'
            $table->unsignedBigInteger('action_by')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allottee_stage_trackers');
    }
};
