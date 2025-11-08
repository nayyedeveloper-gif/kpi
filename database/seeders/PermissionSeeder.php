<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $permissions = [
            // Users
            ['name' => 'view_users', 'display_name' => 'View Users', 'category' => 'users', 'description' => 'Can view user list'],
            ['name' => 'create_users', 'display_name' => 'Create Users', 'category' => 'users', 'description' => 'Can create new users'],
            ['name' => 'edit_users', 'display_name' => 'Edit Users', 'category' => 'users', 'description' => 'Can edit existing users'],
            ['name' => 'delete_users', 'display_name' => 'Delete Users', 'category' => 'users', 'description' => 'Can delete users'],
            
            // KPI
            ['name' => 'view_kpi', 'display_name' => 'View KPI', 'category' => 'kpi', 'description' => 'Can view KPI tracking'],
            ['name' => 'manage_kpi', 'display_name' => 'Manage KPI', 'category' => 'kpi', 'description' => 'Can create and edit KPI measurements'],
            ['name' => 'view_own_kpi', 'display_name' => 'View Own KPI', 'category' => 'kpi', 'description' => 'Can view own KPI only'],
            
            // Analytics
            ['name' => 'view_analytics', 'display_name' => 'View Analytics', 'category' => 'analytics', 'description' => 'Can view analytics dashboard'],
            ['name' => 'view_team_performance', 'display_name' => 'View Team Performance', 'category' => 'analytics', 'description' => 'Can view team performance board'],
            
            // Reports
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'category' => 'reports', 'description' => 'Can view reports'],
            ['name' => 'export_reports', 'display_name' => 'Export Reports', 'category' => 'reports', 'description' => 'Can export reports to PDF/Excel'],
            
            // Departments
            ['name' => 'view_departments', 'display_name' => 'View Departments', 'category' => 'departments', 'description' => 'Can view departments'],
            ['name' => 'manage_departments', 'display_name' => 'Manage Departments', 'category' => 'departments', 'description' => 'Can create, edit, delete departments'],
            
            // Positions
            ['name' => 'view_positions', 'display_name' => 'View Positions', 'category' => 'positions', 'description' => 'Can view positions'],
            ['name' => 'manage_positions', 'display_name' => 'Manage Positions', 'category' => 'positions', 'description' => 'Can create, edit, delete positions'],
            
            // Roles
            ['name' => 'view_roles', 'display_name' => 'View Roles', 'category' => 'roles', 'description' => 'Can view roles'],
            ['name' => 'manage_roles', 'display_name' => 'Manage Roles', 'category' => 'roles', 'description' => 'Can create, edit, delete roles'],
            ['name' => 'assign_permissions', 'display_name' => 'Assign Permissions', 'category' => 'roles', 'description' => 'Can assign permissions to roles'],
            
            // Organization
            ['name' => 'view_organization', 'display_name' => 'View Organization Chart', 'category' => 'organization', 'description' => 'Can view organization chart'],
            
            // Branches
            ['name' => 'view_branches', 'display_name' => 'View Branches', 'category' => 'branches', 'description' => 'Can view branches'],
            ['name' => 'manage_branches', 'display_name' => 'Manage Branches', 'category' => 'branches', 'description' => 'Can create, edit, delete branches'],
            
            // Groups
            ['name' => 'view_groups', 'display_name' => 'View Groups', 'category' => 'groups', 'description' => 'Can view groups'],
            ['name' => 'manage_groups', 'display_name' => 'Manage Groups', 'category' => 'groups', 'description' => 'Can create, edit, delete groups'],
            ['name' => 'assign_group_members', 'display_name' => 'Assign Group Members', 'category' => 'groups', 'description' => 'Can add/remove members from groups'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
