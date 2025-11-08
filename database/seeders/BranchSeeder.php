<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Yangon Main Office',
                'code' => 'YGN-001',
                'address' => 'No. 123, Pyay Road, Kamayut Township, Yangon',
                'phone' => '09-123456789',
                'email' => 'yangon@company.com',
                'manager_id' => null, // Will be set after users are created
                'is_active' => true,
            ],
            [
                'name' => 'Mandalay Branch',
                'code' => 'MDY-001',
                'address' => 'No. 456, 26th Street, Chan Aye Tharzan Township, Mandalay',
                'phone' => '09-987654321',
                'email' => 'mandalay@company.com',
                'manager_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Naypyidaw Branch',
                'code' => 'NPT-001',
                'address' => 'Dekkhinathiri Township, Naypyidaw',
                'phone' => '09-555666777',
                'email' => 'naypyidaw@company.com',
                'manager_id' => null,
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
