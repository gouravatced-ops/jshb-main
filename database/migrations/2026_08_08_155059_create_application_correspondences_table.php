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
        Schema::create('application_correspondences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('generated_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->enum('type', ['LT', 'OO', 'OD'])->default('LT')->comment('LT=Letter, OO=Office Order, OD=Office Draft');
            $table->string('reference_number')->unique();
            $table->string('subject');
            $table->longText('content');
            
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_correspondences');
    }
};
