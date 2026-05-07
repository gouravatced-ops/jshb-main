<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allottees', function (Blueprint $table) {
            $table->id();
            
            // Application Details
            $table->string('application_no')->nullable();
            $table->date('application_date')->nullable();
            $table->string('allotment_no')->nullable();
            $table->date('allotment_date')->nullable();
            
            // Personal Details
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('first_name_hi')->nullable();
            $table->string('middle_name_hi')->nullable();
            $table->string('relation_name')->nullable();
            $table->string('relation_type')->nullable(); // Father, Mother, Husband, etc.
            $table->string('marital_status')->nullable();
            $table->string('gender')->nullable();
            $table->string('category')->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();
            
            // Date of Birth
            $table->date('date_of_birth')->nullable();
            $table->integer('current_age')->nullable();
            $table->text('dob_remark')->nullable();
            
            // Age at Application
            $table->integer('age_at_application_numbers')->nullable();
            $table->string('age_at_application_words')->nullable();
            $table->integer('age_at_application_numbers_hi')->nullable();
            $table->string('age_at_application_words_hi')->nullable();
            
            // Address Details
            $table->string('state')->nullable();
            $table->string('district')->nullable();
            $table->string('pincode')->nullable();
            $table->string('post_office')->nullable();
            $table->string('police_station')->nullable();
            $table->string('state_hi')->nullable();
            $table->string('district_hi')->nullable();
            $table->string('pincode_hi')->nullable();
            $table->string('post_office_hi')->nullable();
            $table->string('police_station_hi')->nullable();
            
            // Contact Details
            $table->string('primary_mobile')->nullable();
            $table->string('whatsapp_no')->nullable();
            $table->string('alternate_mobile')->nullable();
            $table->string('email_id')->nullable();
            $table->string('landline_std_code')->nullable();
            $table->string('landline_number')->nullable();
            
            // Division Reference
            $table->integer('division_id')->nullable();
            
            // Status
            $table->boolean('status')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allottees');
    }
};