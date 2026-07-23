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
        Schema::table('notices', function (Blueprint $table) {
            $table->boolean('notice_in_software')->default(1)->after('message');
            $table->boolean('send_email')->default(0)->after('notice_in_software');
            $table->boolean('send_sms')->default(0)->after('send_email');
            $table->boolean('send_whatsapp')->default(0)->after('send_sms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn(['notice_in_software', 'send_email', 'send_sms', 'send_whatsapp']);
        });
    }
};
