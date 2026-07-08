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
        Schema::create('application_pdf_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id')->index();
            $table->string('document_type', 50);
            $table->string('pdf_file_name', 255);
            $table->string('pdf_file_path', 500);
            $table->unsignedBigInteger('generated_by');
            $table->dateTime('generated_at')->useCurrent();
            $table->longText('pdf_content')->nullable();
            $table->boolean('is_final')->default(0);
            $table->integer('version')->default(1);
            
            $table->foreign('application_id', 'fk_pdf_application')
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
        Schema::dropIfExists('application_pdf_history');
    }
};
