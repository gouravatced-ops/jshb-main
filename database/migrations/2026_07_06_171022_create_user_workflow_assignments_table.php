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
        Schema::create('user_workflow_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('workflow_id')->index();
            $table->unsignedBigInteger('step_id')->index();
            $table->boolean('is_active')->default(1);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            
            $table->foreign('user_id', 'fk_user_workflow_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('workflow_id', 'fk_user_workflow_workflow')
                  ->references('id')
                  ->on('workflows')
                  ->onDelete('cascade');
                  
            $table->foreign('step_id', 'fk_user_workflow_step')
                  ->references('id')
                  ->on('workflow_steps')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_workflow_assignments');
    }
};
