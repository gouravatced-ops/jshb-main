<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_house_requisitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('engineer_detail_id')->nullable()->constrained('engineer_details')->nullOnDelete();
            $table->foreignId('district_id')->constrained()->cascadeOnDelete();
            $table->foreignId('block_id')->constrained('block_lists')->cascadeOnDelete();
            $table->string('guest_house_name');
            $table->text('purpose');
            $table->date('stay_from');
            $table->date('stay_to');
            $table->unsignedInteger('total_guests')->default(1);
            $table->text('remarks')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_house_requisitions');
    }
};
