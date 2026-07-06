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
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workflow_id')->index();
            $table->integer('step_order');
            $table->string('step_name', 100);
            $table->string('step_code', 50);
            $table->unsignedBigInteger('role_id')->index();
            $table->enum('action_type', [
                'view',
                'verify',
                'recommend',
                'approve',
                'reject',
                'send_back',
                'forward',
                'generate_document',
                'site_verification',
                'review',
                'final_approval'
            ]);
            $table->boolean('can_forward')->default(1);
            $table->boolean('can_reject')->default(1);
            $table->boolean('can_send_back')->default(1);
            $table->boolean('can_upload_document')->default(1);
            $table->boolean('can_add_note')->default(1);
            $table->boolean('requires_signature')->default(1);
            $table->boolean('auto_forward')->default(0);
            $table->integer('auto_forward_days')->nullable();
            $table->unsignedBigInteger('next_step_id')->nullable()->index();
            $table->unsignedBigInteger('previous_step_id')->nullable()->index();
            $table->boolean('is_final_step')->default(0);
            $table->boolean('is_starting_step')->default(0);
            $table->text('notification_template')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            
            $table->foreign('workflow_id', 'fk_workflow_steps_workflow')
                  ->references('id')
                  ->on('workflows')
                  ->onDelete('cascade');
                  
            $table->foreign('role_id', 'fk_workflow_steps_role')
                  ->references('id')
                  ->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
