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
        Schema::create('batch_program_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_program_id')->constrained('batch_programs')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('application_id')->nullable();
            $table->string('status')->default('queued'); // queued, sent, failed
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_program_details');
    }
};
