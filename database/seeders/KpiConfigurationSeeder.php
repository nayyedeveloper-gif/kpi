<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KpiConfiguration;
use App\Models\Role;

class KpiConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles - you may need to adjust these based on your actual role names
        $kpiCheckerRole = Role::where('name', 'kpi_checker')->first();
        $salesRole = Role::where('name', 'sales')->first();
        $customerRole = Role::where('name', 'customer')->first();
        
        // If roles don't exist, create them
        if (!$kpiCheckerRole) {
            $kpiCheckerRole = Role::create([
                'name' => 'kpi_checker',
                'display_name' => 'KPI Checker',
                'description' => 'Role for KPI quality assurance and checking',
            ]);
        }
        
        if (!$salesRole) {
            $salesRole = Role::create([
                'name' => 'sales',
                'display_name' => 'Sales',
                'description' => 'Sales role',
            ]);
        }
        
        if (!$customerRole) {
            $customerRole = Role::create([
                'name' => 'customer',
                'display_name' => 'Customer Service',
                'description' => 'Customer service role',
            ]);
        }

        // Operation Level Configuration (Sales)
        KpiConfiguration::create([
            'name' => 'Operation Level - Sales Tracking',
            'level_type' => 'operation',
            'checker_role_id' => $kpiCheckerRole->id,
            'target_role_id' => $salesRole->id,
            'cascade_enabled' => true,
            'max_cascade_levels' => 5,
            'is_active' => true,
            'description' => 'KPI tracking configuration for operation level sales staff with cascading accountability to supervisors.',
            'good_impact' => [
                0 => 10.0,  // Sales Person
                1 => 3.0,   // Leader
                2 => 2.0,   // Supervisor
                3 => 1.0,   // Assistant Manager
                4 => 0.5,   // Manager
            ],
            'bad_impact' => [
                0 => -10.0,  // Sales Person
                1 => -5.0,   // Leader
                2 => -3.0,   // Supervisor
                3 => -2.0,   // Assistant Manager
                4 => -1.0,   // Manager
            ],
        ]);

        // Entry Level Configuration (Customer Service)
        KpiConfiguration::create([
            'name' => 'Entry Level - Customer Service Tracking',
            'level_type' => 'entry',
            'checker_role_id' => $kpiCheckerRole->id,
            'target_role_id' => $customerRole->id,
            'cascade_enabled' => true,
            'max_cascade_levels' => 5,
            'is_active' => true,
            'description' => 'KPI tracking configuration for entry level customer service staff with cascading accountability.',
            'good_impact' => [
                0 => 10.0,  // Customer Service Rep
                1 => 3.0,   // Team Leader
                2 => 2.0,   // Supervisor
                3 => 1.0,   // Assistant Manager
                4 => 0.5,   // Manager
            ],
            'bad_impact' => [
                0 => -10.0,  // Customer Service Rep
                1 => -5.0,   // Team Leader
                2 => -3.0,   // Supervisor
                3 => -2.0,   // Assistant Manager
                4 => -1.0,   // Manager
            ],
        ]);

        $this->command->info('KPI Configurations seeded successfully!');
    }
}
