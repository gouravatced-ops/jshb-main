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
        Schema::create('application_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id')->index();
            $table->unsignedBigInteger('movement_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('role_id')->index();
            $table->enum('note_type', [
                'general',
                'recommendation',
                'approval',
                'rejection',
                'send_back',
                'query',
                'clarification',
                'verification',
                'site_visit',
                'final_decision'
            ])->default('general');
            $table->text('remarks');
            $table->text('signature')->nullable();
            $table->enum('signature_type', ['digital', 'handwritten', 'e-sign'])->default('digital');
            $table->dateTime('signature_date')->nullable();
            $table->boolean('is_confidential')->default(0);
            $table->boolean('is_public')->default(1);
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            
            $table->foreign('application_id', 'fk_notes_application')
                  ->references('id')
                  ->on('applications')
                  ->onDelete('cascade');
                  
            $table->foreign('movement_id', 'fk_notes_movement')
                  ->references('id')
                  ->on('application_movements')
                  ->onDelete('set null');
                  
            $table->foreign('user_id', 'fk_notes_user')
                  ->references('id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_notes');
    }
};
