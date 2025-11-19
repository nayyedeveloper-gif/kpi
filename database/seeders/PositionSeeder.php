<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hierarchy Level: 1-15 (1 = Highest, 15 = Entry Level)
        $positions = [
            ['name' => 'CEO', 'description' => 'Chief Executive Officer', 'hierarchy_level' => 1],
            ['name' => 'MD', 'description' => 'Managing Director', 'hierarchy_level' => 1],
            ['name' => 'Director', 'description' => 'Director', 'hierarchy_level' => 2],
            ['name' => 'General Manager', 'description' => 'General Manager', 'hierarchy_level' => 3],
            ['name' => 'Deputy General Manager', 'description' => 'Deputy General Manager', 'hierarchy_level' => 4],
            ['name' => 'Assistant General Manager', 'description' => 'Assistant General Manager', 'hierarchy_level' => 5],
            ['name' => 'Manager', 'description' => 'Manager', 'hierarchy_level' => 6],
            ['name' => 'Assistant Manager', 'description' => 'Assistant Manager', 'hierarchy_level' => 7],
            ['name' => 'Supervisor', 'description' => 'Supervisor', 'hierarchy_level' => 8],
            ['name' => 'Leader', 'description' => 'Team Leader', 'hierarchy_level' => 9],
            ['name' => 'Sales', 'description' => 'Sales Representative', 'hierarchy_level' => 10],
            ['name' => 'Customer Service', 'description' => 'Customer Service Staff (Entry Level)', 'hierarchy_level' => 11],
        ];

        foreach ($positions as $position) {
            Position::updateOrCreate(['name' => $position['name']], $position);
        }
    }
}