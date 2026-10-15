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
        // 1. future_allottees table on adms_allottees connection
        Schema::connection('adms_allottees')->create('future_allottees', function (Blueprint $table) {
            $table->id();
            $table->string('reference_portal_id', 100)->unique();
            $table->string('application_no', 100)->nullable();
            $table->integer('application_day')->nullable();
            $table->integer('application_month')->nullable();
            $table->integer('application_year')->nullable();

            // Location & Property Relations
            $table->unsignedBigInteger('scheme_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('subdivision_id')->nullable();
            $table->unsignedBigInteger('pcategory_id')->nullable();
            $table->unsignedBigInteger('property_type_id')->nullable();
            $table->unsignedBigInteger('p_sub_type_id')->nullable();
            $table->unsignedBigInteger('quarter_id')->nullable();

            // Personal Information
            $table->string('prefix', 20)->nullable();
            $table->string('allottee_name', 100);
            $table->string('allottee_middle_name', 100)->nullable();
            $table->string('allottee_surname', 100)->nullable();
            $table->string('allottee_prefix_hindi', 20)->nullable();
            $table->string('allottee_name_hindi', 100)->nullable();
            $table->string('allottee_middle_hindi', 100)->nullable();
            $table->string('allottee_surname_hindi', 100)->nullable();

            // Relation Information
            $table->string('allottee_relation_type', 50)->nullable();
            $table->string('relation_name', 150)->nullable();
            $table->string('relation_prefix_hindi', 20)->nullable();
            $table->string('relation_name_hindi', 150)->nullable();

            // Demographics & Identity
            $table->string('marital_status', 50)->nullable();
            $table->string('allottee_gender', 20)->nullable();
            $table->string('pan_card_number', 20)->nullable();
            $table->string('aadhar_card_number', 20)->nullable();
            $table->string('allottee_category', 100)->nullable();
            $table->string('allottee_category_hindi', 150)->nullable();
            $table->string('allottee_religion', 50)->nullable();
            $table->string('allottee_nationality', 50)->default('Indian');

            // Date of Birth
            $table->integer('date_of_birth_day')->nullable();
            $table->integer('date_of_birth_month')->nullable();
            $table->integer('date_of_birth_year')->nullable();

            // Contact Information
            $table->string('email', 150)->nullable();
            $table->string('mobile', 20)->nullable();

            // Remarks
            $table->text('remarks')->nullable();

            // Migration / Sync Tracking Fields
            $table->enum('sync_status', ['pending', 'synced', 'failed'])->default('pending')->index();
            $table->unsignedBigInteger('synced_allottee_id')->nullable();
            $table->unsignedBigInteger('synced_application_id')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->text('error_log')->nullable();

            $table->timestamps();
        });

        // 2. future_allottee_contact_details table on adms_allottees connection
        Schema::connection('adms_allottees')->create('future_allottee_contact_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('future_allottee_id')->index();

            $table->string('relation_type', 50)->nullable();
            $table->string('prefix_relation_eng', 20)->nullable();
            $table->string('relation_name', 150)->nullable();
            $table->string('prefix_relation_hindi', 20)->nullable();
            $table->string('relation_name_hindi', 150)->nullable();

            // Relation Address
            $table->text('relation_address')->nullable();
            $table->text('relation_address_hindi')->nullable();
            $table->string('relation_state', 100)->nullable();
            $table->string('relation_district', 100)->nullable();
            $table->string('relation_pincode', 20)->nullable();
            $table->string('relation_post_office', 100)->nullable();
            $table->string('relation_post_office_hindi', 100)->nullable();
            $table->string('relation_police_station', 100)->nullable();
            $table->string('relation_police_station_hindi', 100)->nullable();
            $table->boolean('same_as_relation_copy')->default(false);

            // Present Address
            $table->text('present_address')->nullable();
            $table->text('present_address_hindi')->nullable();
            $table->string('present_state', 100)->nullable();
            $table->string('present_district', 100)->nullable();
            $table->string('present_pincode', 20)->nullable();
            $table->string('present_post_office', 100)->nullable();
            $table->string('present_post_office_hindi', 100)->nullable();
            $table->string('present_police_station', 100)->nullable();
            $table->string('present_police_station_hindi', 100)->nullable();
            $table->boolean('same_as_present_place_residance')->default(false);

            // Permanent Address
            $table->text('permanent_address')->nullable();
            $table->text('permanent_address_hindi')->nullable();
            $table->string('permanent_state', 100)->nullable();
            $table->string('permanent_district', 100)->nullable();
            $table->string('permanent_pincode', 20)->nullable();
            $table->string('permanent_post_office', 100)->nullable();
            $table->string('permanent_post_office_hindi', 100)->nullable();
            $table->string('permanent_police_station', 100)->nullable();
            $table->string('permanent_police_station_hindi', 100)->nullable();
            $table->boolean('same_as_permanent_address')->default(false);

            $table->timestamps();
        });

        // 3. future_allottee_transactions table on adms_allottees connection
        Schema::connection('adms_allottees')->create('future_allottee_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('future_allottee_id')->index();

            $table->string('transaction_type', 50)->default('lottery_payment');
            $table->string('payment_stage', 50)->default('application');
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->string('payment_mode', 50)->default('online');
            $table->string('payment_status', 50)->default('success');
            $table->string('transaction_no', 100)->nullable();
            $table->string('utr_no', 100)->nullable();
            $table->string('receipt_file', 255)->nullable();
            $table->string('receipt_path', 500)->nullable();
            $table->integer('payment_day')->nullable();
            $table->integer('payment_month')->nullable();
            $table->integer('payment_year')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('adms_allottees')->dropIfExists('future_allottee_transactions');
        Schema::connection('adms_allottees')->dropIfExists('future_allottee_contact_details');
        Schema::connection('adms_allottees')->dropIfExists('future_allottees');
    }
};
