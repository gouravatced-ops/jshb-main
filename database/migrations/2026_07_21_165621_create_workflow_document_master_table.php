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
        Schema::create('workflow_document_master', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('document_master_id'); // ID from adms_allottees DB
            $table->boolean('is_required')->default(true);
            $table->timestamps();
            
            $table->unique(['workflow_id', 'document_master_id'], 'wf_doc_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_document_master');
    }
};
