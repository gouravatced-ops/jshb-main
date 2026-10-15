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
            ['id' => 1,  'name' => 'Dealing Assistant',          'slug' => 'dealing-assistant',          'short_name' => 'DA'],
            ['id' => 2,  'name' => 'Office Superintendent',      'slug' => 'office-superintendent',      'short_name' => 'OS'],
            ['id' => 3,  'name' => 'Estate Officer',              'slug' => 'estate-officer',              'short_name' => 'EO'],
            ['id' => 4,  'name' => 'Managing Director',           'slug' => 'managing-director',           'short_name' => 'MD'],
            ['id' => 5,  'name' => 'Executive Engineer',          'slug' => 'executive-engineer',          'short_name' => 'EE'],
            ['id' => 6,  'name' => 'Assistant Engineer',          'slug' => 'assistant-engineer',          'short_name' => 'AE'],
            ['id' => 7,  'name' => 'Junior Engineer',             'slug' => 'junior-engineer',             'short_name' => 'JE'],
            ['id' => 8,  'name' => 'Admin',                       'slug' => 'admin',                       'short_name' => 'AD'],
            ['id' => 9,  'name' => 'Super Admin',                 'slug' => 'super-admin',                 'short_name' => 'SA'],
            ['id' => 10, 'name' => 'Operator',                    'slug' => 'operator',                    'short_name' => 'OP'],
            ['id' => 11, 'name' => 'Staff',                       'slug' => 'staff',                       'short_name' => 'STAFF'],
            ['id' => 12, 'name' => 'Division Officer',            'slug' => 'division-officer',            'short_name' => 'DO'],
            ['id' => 13, 'name' => 'Revenue Officer',             'slug' => 'revenue-officer',             'short_name' => 'RO'],
            ['id' => 14, 'name' => 'Chief Accounts Officer',      'slug' => 'chief-accounts-officer',      'short_name' => 'CAO'],
            ['id' => 15, 'name' => 'Chief Financial Officer',     'slug' => 'chief-financial-officer',     'short_name' => 'CFO'],
            ['id' => 16, 'name' => 'Secretary Chief Engineer',    'slug' => 'secretary-chief-engineer',    'short_name' => 'SCE'],
            ['id' => 17, 'name' => 'Allottee',                    'slug' => 'allottee',                    'short_name' => 'ALT'],
            ['id' => 18, 'name' => 'Co-Assistant',                'slug' => 'co-assistant',                'short_name' => 'CA'],
            ['id' => 19, 'name' => 'Headquarter',                'slug' => 'headquarter',                'short_name' => 'HQ'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['id' => $role['id']],
                [
                    'name' => $role['name'],
                    'slug' => $role['slug'],
                    'short_name' => $role['short_name'],
                    'status' => true,
                ]
            );
        }
    }
}
