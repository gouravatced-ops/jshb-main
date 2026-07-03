<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add otp_login_valid_until to users table
        // When OTP login succeeds, this is set to +8 hours
        // Next login within 8 hours skips OTP requirement
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('otp_login_valid_until')->nullable()->after('login_with_otp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('otp_login_valid_until');
        });
    }
};
