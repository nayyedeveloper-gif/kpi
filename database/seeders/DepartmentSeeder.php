<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Sales', 'description' => 'Sales Department'],
            ['name' => 'Marketing', 'description' => 'Marketing Department'],
            ['name' => 'Operations', 'description' => 'Operations Department'],
            ['name' => 'Human Resources', 'description' => 'Human Resources Department'],
            ['name' => 'Finance', 'description' => 'Finance Department'],
            ['name' => 'IT', 'description' => 'Information Technology Department'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}