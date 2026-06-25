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
        Schema::create('allottee_site_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allottee_id')->constrained('allottees')->cascadeOnDelete();
            $table->string('colony_name')->nullable();
            $table->string('allottee_name')->nullable();
            $table->string('unit_number')->nullable();
            $table->string('unit_use')->nullable();
            $table->string('road_front')->nullable();
            $table->string('road_beside')->nullable();
            $table->string('plot_size_allotment')->nullable();
            $table->string('plot_size_agreement')->nullable();
            $table->string('plot_size_possession')->nullable();
            $table->string('plot_size_difference_reason')->nullable();
            $table->string('encroachment_area')->nullable();
            $table->string('encroachment_public_use')->nullable();
            $table->string('encroachment_independent_use')->nullable();
            $table->string('encroachment_future_use')->nullable();
            $table->string('encroachment_settlement')->nullable();
            $table->string('is_house_constructed')->nullable();
            $table->string('approved_map_authority')->nullable();
            $table->string('approved_map_case')->nullable();
            $table->date('approved_map_date')->nullable();
            $table->string('is_construction_as_per_map')->nullable();
            $table->string('alteration_map_authority')->nullable();
            $table->string('alteration_map_case')->nullable();
            $table->date('alteration_map_date')->nullable();
            $table->json('map_parameters')->nullable();
            $table->longText('map_image')->nullable(); // Store Base64 Image string for simplicity
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allottee_site_verifications');
    }
};
