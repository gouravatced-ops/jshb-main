<?php

use App\Support\SensitiveData;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('secure_pin')->nullable()->after('password');
        });

        Schema::table('engineer_details', function (Blueprint $table) {
            $table->text('state_government_engineer_id')->change();
            $table->text('aadhar_no')->nullable()->change();
            $table->text('pan_card_no')->nullable()->change();
            $table->string('state_government_engineer_id_hash')->nullable()->after('state_government_engineer_id');
            $table->string('aadhar_no_hash')->nullable()->after('aadhar_no');
            $table->string('pan_card_no_hash')->nullable()->after('pan_card_no');
        });

        Schema::table('user_details', function (Blueprint $table) {
            $table->text('phone')->nullable()->change();
            $table->string('phone_hash')->nullable()->after('phone');
        });

        DB::table('users')
            ->where('role', 'admin')
            ->whereNull('secure_pin')
            ->update([
                'secure_pin' => Hash::make('12345'),
            ]);

        DB::table('engineer_details')->orderBy('id')->get()->each(function ($row) {
            DB::table('engineer_details')
                ->where('id', $row->id)
                ->update([
                    'state_government_engineer_id' => SensitiveData::encrypt($row->state_government_engineer_id),
                    'state_government_engineer_id_hash' => SensitiveData::hash($row->state_government_engineer_id),
                    'aadhar_no' => SensitiveData::encrypt($row->aadhar_no),
                    'aadhar_no_hash' => SensitiveData::hash($row->aadhar_no),
                    'pan_card_no' => SensitiveData::encrypt($row->pan_card_no),
                    'pan_card_no_hash' => SensitiveData::hash($row->pan_card_no),
                ]);
        });

        DB::table('user_details')->orderBy('id')->get()->each(function ($row) {
            DB::table('user_details')
                ->where('id', $row->id)
                ->update([
                    'phone' => SensitiveData::encrypt($row->phone),
                    'phone_hash' => SensitiveData::hash($row->phone),
                ]);
        });

        Schema::table('engineer_details', function (Blueprint $table) {
            $table->unique('state_government_engineer_id_hash', 'engineer_details_govt_id_hash_unique');
        });
    }

    public function down(): void
    {
        Schema::table('engineer_details', function (Blueprint $table) {
            $table->dropUnique('engineer_details_govt_id_hash_unique');
            $table->dropColumn(['state_government_engineer_id_hash', 'aadhar_no_hash', 'pan_card_no_hash']);
        });

        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn('phone_hash');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('secure_pin');
        });
    }
};
