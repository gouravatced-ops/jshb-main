<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('engineer_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            $table->foreignId('current_organization_id')->constrained('organizations');
            $table->foreignId('parent_organization_id')->nullable()
                ->constrained('organizations')->nullOnDelete();

            $table->foreignId('district_id')->constrained()->cascadeOnDelete();
            $table->foreignId('block_id')->constrained('block_lists')->cascadeOnDelete();

            $table->foreignId('division_id')->nullable()
                ->constrained()->nullOnDelete();

            $table->foreignId('sub_division_id')->nullable()
                ->constrained()->nullOnDelete();

            $table->foreignId('post_type_id')->constrained()->cascadeOnDelete();

            $table->string('employee_name');
            $table->string('employee_hindi_name')->nullable();

            $table->string('state_government_engineer_id')->unique();

            $table->date('date_of_birth')->nullable();
            $table->date('anniversary_date')->nullable();

            $table->string('spouse_name')->nullable();
            $table->unsignedInteger('no_of_children')->nullable();

            $table->string('aadhar_no', 20)->nullable();
            $table->string('pan_card_no', 20)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('engineer_details');
    }
};
