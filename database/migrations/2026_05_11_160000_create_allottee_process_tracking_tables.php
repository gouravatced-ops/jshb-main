<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('allottees', function (Blueprint $table) {
            if (!Schema::hasColumn('allottees', 'payment_option')) {
                $table->string('payment_option', 20)->nullable()->after('payment_reference');
            }
            if (!Schema::hasColumn('allottees', 'payment_option_selected_at')) {
                $table->timestamp('payment_option_selected_at')->nullable()->after('payment_option');
            }
            if (!Schema::hasColumn('allottees', 'remaining_amount')) {
                $table->decimal('remaining_amount', 14, 2)->nullable()->after('payment_option_selected_at');
            }
            if (!Schema::hasColumn('allottees', 'emi_months')) {
                $table->unsignedSmallInteger('emi_months')->default(60)->after('remaining_amount');
            }
            if (!Schema::hasColumn('allottees', 'emi_monthly_amount')) {
                $table->decimal('emi_monthly_amount', 14, 2)->nullable()->after('emi_months');
            }
            if (!Schema::hasColumn('allottees', 'final_calculation_generated')) {
                $table->boolean('final_calculation_generated')->default(false)->after('emi_monthly_amount');
            }
            if (!Schema::hasColumn('allottees', 'recalculation_allowed')) {
                $table->boolean('recalculation_allowed')->default(true)->after('final_calculation_generated');
            }
        });

        Schema::create('allottee_process_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allottee_id');
            $table->unsignedTinyInteger('step_no');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('status', ['locked', 'pending', 'completed'])->default('locked');
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('completed_by')->nullable();
            $table->timestamps();

            $table->unique(['allottee_id', 'step_no']);
            $table->index(['allottee_id', 'status']);
        });

        Schema::create('allottee_generated_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allottee_id');
            $table->string('document_type', 50);
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->index(['allottee_id', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allottee_generated_documents');
        Schema::dropIfExists('allottee_process_steps');

        Schema::table('allottees', function (Blueprint $table) {
            $table->dropColumn([
                'payment_option',
                'payment_option_selected_at',
                'remaining_amount',
                'emi_months',
                'emi_monthly_amount',
                'final_calculation_generated',
                'recalculation_allowed',
            ]);
        });
    }
};
