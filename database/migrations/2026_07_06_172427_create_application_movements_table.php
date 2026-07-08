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
        Schema::create('application_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id')->index();
            $table->unsignedBigInteger('from_user_id')->nullable()->index();
            $table->unsignedBigInteger('to_user_id')->nullable()->index();
            $table->unsignedBigInteger('from_role_id')->nullable()->index();
            $table->unsignedBigInteger('to_role_id')->nullable()->index();
            $table->unsignedBigInteger('from_step_id')->nullable();
            $table->unsignedBigInteger('to_step_id')->nullable();
            $table->enum('action_type', [
                'created',
                'forwarded',
                'recommended',
                'approved',
                'rejected',
                'send_back',
                'received',
                'completed',
                'cancelled',
                'verified',
                'site_verified',
                'document_generated'
            ]);
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'rejected',
                'send_back',
                'cancelled'
            ])->default('pending');
            $table->text('remarks')->nullable();
            $table->dateTime('movement_date')->useCurrent();
            $table->dateTime('received_date')->nullable();
            $table->boolean('is_read')->default(0);
            $table->dateTime('read_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            
            $table->foreign('application_id', 'fk_movements_application')
                  ->references('id')
                  ->on('applications')
                  ->onDelete('cascade');
                  
            $table->foreign('from_user_id', 'fk_movements_from_user')
                  ->references('id')
                  ->on('users');
                  
            $table->foreign('to_user_id', 'fk_movements_to_user')
                  ->references('id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_movements');
    }
};
