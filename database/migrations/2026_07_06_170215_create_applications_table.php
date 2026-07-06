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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_no', 50)->unique('application_no_unique');
            $table->enum('application_type', [
                'allotment',
                'agreement',
                'possession',
                'registry',
                'mutation',
                'transfer',
                'noc',
                'lease_renewal',
                'duplicate_certificate',
                'cancellation',
                'name_correction'
            ]);
            $table->unsignedBigInteger('allottee_id')->index();
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('workflow_id')->index();
            $table->unsignedBigInteger('current_step_id')->nullable();
            $table->unsignedBigInteger('current_user_id')->nullable()->index();
            $table->unsignedBigInteger('current_role_id')->nullable();
            $table->enum('status', [
                'draft',
                'pending',
                'in_progress',
                'forwarded',
                'recommended',
                'approved',
                'rejected',
                'send_back',
                'completed',
                'cancelled'
            ])->default('draft')->index();
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->dateTime('created_date')->useCurrent();
            $table->dateTime('completed_date')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            $table->softDeletes();
            
            $table->foreign('allottee_id', 'fk_applications_allottee')
                  ->references('id')
                  ->on('allottees')
                  ->onDelete('cascade');
                  
            $table->foreign('workflow_id', 'fk_applications_workflow')
                  ->references('id')
                  ->on('workflows');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
