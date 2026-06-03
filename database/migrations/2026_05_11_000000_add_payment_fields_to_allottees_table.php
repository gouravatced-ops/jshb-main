<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('allottees', function (Blueprint $table) {
            if (!Schema::hasColumn('allottees', 'payment_amount')) {
                $table->decimal('payment_amount', 14, 2)->nullable();
            }
            if (!Schema::hasColumn('allottees', 'payment_date')) {
                $table->date('payment_date')->nullable();
            }
            if (!Schema::hasColumn('allottees', 'payment_mode')) {
                $table->string('payment_mode', 100)->nullable();
            }
            if (!Schema::hasColumn('allottees', 'payment_reference')) {
                $table->string('payment_reference', 255)->nullable();
            }
            if (!Schema::hasColumn('allottees', 'payment_receipt_path')) {
                $table->string('payment_receipt_path', 500)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('allottees', function (Blueprint $table) {
            $table->dropColumn([
                'payment_amount',
                'payment_date',
                'payment_mode',
                'payment_reference',
                'payment_receipt_path',
            ]);
        });
    }
};
