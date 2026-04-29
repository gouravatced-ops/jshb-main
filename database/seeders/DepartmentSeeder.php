<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Water Resources', 'department_code' => 'WRD', 'status' => true],
            ['name' => 'Road Construction', 'department_code' => 'RCD', 'status' => true],
            ['name' => 'Building Construction', 'department_code' => 'BCD', 'status' => true],
            ['name' => 'Rural Development', 'department_code' => 'RDD', 'status' => true],
            ['name' => 'Drinking Water & Sanitation', 'department_code' => 'DWSD', 'status' => true],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['name' => $department['name']],
                $department
            );
        }
    }
}
