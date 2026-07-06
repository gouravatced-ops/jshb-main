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
        Schema::create('application_status_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id')->index();
            $table->enum('status_from', [
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
            ])->nullable();
            $table->enum('status_to', [
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
            ]);
            $table->unsignedBigInteger('changed_by');
            $table->text('remarks')->nullable();
            $table->dateTime('changed_at')->useCurrent();
            
            $table->foreign('application_id', 'fk_status_history_application')
                  ->references('id')
                  ->on('applications')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_status_history');
    }
};
