<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'Dealing Assistant', 'slug' => 'dealing-assistant', 'short_name' => 'DA'],
            ['id' => 2, 'name' => 'Office Superintendent', 'slug' => 'office-superintendent', 'short_name' => 'OS'],
            ['id' => 3, 'name' => 'Estate Officer', 'slug' => 'estate-officer', 'short_name' => 'EO'],
            ['id' => 4, 'name' => 'Managing Director', 'slug' => 'managing-director', 'short_name' => 'MD'],
            ['id' => 5, 'name' => 'Executive Engineer', 'slug' => 'executive-engineer', 'short_name' => 'EE'],
            ['id' => 6, 'name' => 'Assistant Engineer', 'slug' => 'assistant-engineer', 'short_name' => 'AE'],
            ['id' => 7, 'name' => 'Junior Engineer', 'slug' => 'junior-engineer', 'short_name' => 'JE'],
            ['id' => 8, 'name' => 'Admin', 'slug' => 'admin', 'short_name' => 'AD'],
            ['id' => 9, 'name' => 'Super Admin', 'slug' => 'super-admin', 'short_name' => 'SA'],
            ['id' => 10, 'name' => 'Operator', 'slug' => 'operator', 'short_name' => 'OP'],
            ['id' => 11, 'name' => 'Staff', 'slug' => 'staff', 'short_name' => 'STAFF'],
            ['id' => 12, 'name' => 'Allottee', 'slug' => 'allottee', 'short_name' => 'ALLOTTEE'],
            ['id' => 13, 'name' => 'Division Officer', 'slug' => 'division-officer', 'short_name' => 'DO'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['id' => $role['id']],
                [
                    'name' => $role['name'],
                    'short_name' => $role['short_name'],
                    'status' => true,
                ]
            );
        }
    }
}
