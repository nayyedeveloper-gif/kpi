<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Sales Team A',
                'code' => 'ST-A',
                'description' => 'Primary sales team focusing on corporate clients',
                'group_type' => 'team',
                'leader_id' => null, // Will be set after users are created
                'branch_id' => 1, // Yangon Main Office
                'is_active' => true,
            ],
            [
                'name' => 'Project Alpha',
                'code' => 'PRJ-ALPHA',
                'description' => 'Digital transformation project team',
                'group_type' => 'project',
                'leader_id' => null,
                'branch_id' => null, // Cross-branch project
                'is_active' => true,
            ],
            [
                'name' => 'Quality Control Committee',
                'code' => 'QCC-001',
                'description' => 'Committee for quality assurance and control',
                'group_type' => 'committee',
                'leader_id' => null,
                'branch_id' => null, // Cross-branch committee
                'is_active' => true,
            ],
            [
                'name' => 'Marketing Team',
                'code' => 'MKT-001',
                'description' => 'Marketing and brand management team',
                'group_type' => 'team',
                'leader_id' => null,
                'branch_id' => 1, // Yangon Main Office
                'is_active' => true,
            ],
            [
                'name' => 'IT Support Team',
                'code' => 'IT-SUP',
                'description' => 'Technical support and infrastructure team',
                'group_type' => 'team',
                'leader_id' => null,
                'branch_id' => 2, // Mandalay Branch
                'is_active' => true,
            ],
        ];

        foreach ($groups as $group) {
            Group::create($group);
        }
    }
}
