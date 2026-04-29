<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_types', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('post_types')->insert([
            ['level' => 'Top-Level (Executive / Head)', 'name' => 'Chief Engineer (CE)', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Top-Level (Executive / Head)', 'name' => 'Engineer-in-Chief (E-in-C)', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Top-Level (Executive / Head)', 'name' => 'Director (Engineering)', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Top-Level (Executive / Head)', 'name' => 'Technical Director', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['level' => 'Senior Management', 'name' => 'Superintending Engineer (SE)', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Senior Management', 'name' => 'Senior General Manager (Engineering)', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Senior Management', 'name' => 'General Manager (Engineering)', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['level' => 'Middle Management', 'name' => 'Executive Engineer (EE)', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Middle Management', 'name' => 'Deputy General Manager (DGM - Engineering)', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Middle Management', 'name' => 'Project Manager', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Middle Management', 'name' => 'Divisional Engineer', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['level' => 'Junior Management', 'name' => 'Assistant Executive Engineer (AEE)', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Junior Management', 'name' => 'Assistant Engineer (AE)', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Junior Management', 'name' => 'Deputy Engineer', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['level' => 'Entry-Level / Field Roles', 'name' => 'Junior Engineer (JE)', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Entry-Level / Field Roles', 'name' => 'Section Engineer', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Entry-Level / Field Roles', 'name' => 'Site Engineer', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Entry-Level / Field Roles', 'name' => 'Field Engineer', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Entry-Level / Field Roles', 'name' => 'Trainee Engineer / Graduate Engineer Trainee (GET)', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['level' => 'Specialized Roles (Non-IT but domain-specific)', 'name' => 'Design Engineer', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Specialized Roles (Non-IT but domain-specific)', 'name' => 'Planning Engineer', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Specialized Roles (Non-IT but domain-specific)', 'name' => 'Quality Control Engineer', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Specialized Roles (Non-IT but domain-specific)', 'name' => 'Maintenance Engineer', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Specialized Roles (Non-IT but domain-specific)', 'name' => 'Production Engineer', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['level' => 'Specialized Roles (Non-IT but domain-specific)', 'name' => 'Safety Engineer', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('post_types');
    }
};
