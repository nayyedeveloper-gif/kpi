<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles, departments, and positions
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        $departments = Department::all();
        $positions = Position::orderBy('hierarchy_level')->get();

        // CEO (Top Level - No Supervisor)
        $ceo = User::create([
            'name' => 'Nay Zaw Oo',
            'email' => 'nayzawoo@company.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'department_id' => $departments->first()->id,
            'position_id' => $positions->where('hierarchy_level', 1)->first()->id,
            'supervisor_id' => null,
            'phone_number' => '+95-9-123-456-789',
            'is_active' => true,
        ]);

        // Directors (Report to CEO)
        $director1 = User::create([
            'name' => 'Ma Yu Mon',
            'email' => 'mayumon@company.com',
            'password' => Hash::make('password'),
            'role_id' => $managerRole->id,
            'department_id' => $departments->skip(1)->first()->id ?? $departments->first()->id,
            'position_id' => $positions->where('hierarchy_level', 2)->first()->id,
            'supervisor_id' => $ceo->id,
            'phone_number' => '+95-9-234-567-890',
            'is_active' => true,
        ]);

        $director2 = User::create([
            'name' => 'Ni Ni Kyaw Lwin',
            'email' => 'ninikyawlwin@company.com',
            'password' => Hash::make('password'),
            'role_id' => $managerRole->id,
            'department_id' => $departments->skip(2)->first()->id ?? $departments->first()->id,
            'position_id' => $positions->where('hierarchy_level', 2)->first()->id,
            'supervisor_id' => $ceo->id,
            'phone_number' => '+95-9-345-678-901',
            'is_active' => true,
        ]);

        // Managers (Report to Directors)
        $manager1 = User::create([
            'name' => 'Kyaw Zin Htet',
            'email' => 'kyawzinhtet@company.com',
            'password' => Hash::make('password'),
            'role_id' => $supervisorRole->id,
            'department_id' => $director1->department_id,
            'position_id' => $positions->where('hierarchy_level', 3)->first()->id,
            'supervisor_id' => $director1->id,
            'phone_number' => '+95-9-456-789-012',
            'is_active' => true,
        ]);

        $manager2 = User::create([
            'name' => 'Thida Aye',
            'email' => 'thidaaye@company.com',
            'password' => Hash::make('password'),
            'role_id' => $supervisorRole->id,
            'department_id' => $director2->department_id,
            'position_id' => $positions->where('hierarchy_level', 3)->first()->id,
            'supervisor_id' => $director2->id,
            'phone_number' => '+95-9-567-890-123',
            'is_active' => true,
        ]);

        // Supervisors (Report to Managers)
        $supervisor1 = User::create([
            'name' => 'Aung Aung',
            'email' => 'aungaung@company.com',
            'password' => Hash::make('password'),
            'role_id' => $supervisorRole->id,
            'department_id' => $manager1->department_id,
            'position_id' => $positions->where('hierarchy_level', 8)->first()->id,
            'supervisor_id' => $manager1->id,
            'phone_number' => '+95-9-678-901-234',
            'is_active' => true,
        ]);

        $supervisor2 = User::create([
            'name' => 'Zaw Min Oo',
            'email' => 'zawminoo@company.com',
            'password' => Hash::make('password'),
            'role_id' => $supervisorRole->id,
            'department_id' => $manager1->department_id,
            'position_id' => $positions->where('hierarchy_level', 8)->first()->id,
            'supervisor_id' => $manager1->id,
            'phone_number' => '+95-9-789-012-345',
            'is_active' => true,
        ]);

        $supervisor3 = User::create([
            'name' => 'Khin Myo Chit',
            'email' => 'khinmyochit@company.com',
            'password' => Hash::make('password'),
            'role_id' => $supervisorRole->id,
            'department_id' => $manager2->department_id,
            'position_id' => $positions->where('hierarchy_level', 8)->first()->id,
            'supervisor_id' => $manager2->id,
            'phone_number' => '+95-9-890-123-456',
            'is_active' => true,
        ]);

        // Sales Staff (Report to Supervisors)
        User::create([
            'name' => 'Htet Htet Aung',
            'email' => 'htethtetaung@company.com',
            'password' => Hash::make('password'),
            'role_id' => $employeeRole->id,
            'department_id' => $supervisor1->department_id,
            'position_id' => $positions->where('hierarchy_level', 10)->first()->id,
            'supervisor_id' => $supervisor1->id,
            'phone_number' => '+95-9-901-234-567',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Mya Mya Win',
            'email' => 'myamyawin@company.com',
            'password' => Hash::make('password'),
            'role_id' => $employeeRole->id,
            'department_id' => $supervisor1->department_id,
            'position_id' => $positions->where('hierarchy_level', 10)->first()->id,
            'supervisor_id' => $supervisor1->id,
            'phone_number' => '+95-9-012-345-678',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Min Min Oo',
            'email' => 'minminoo@company.com',
            'password' => Hash::make('password'),
            'role_id' => $employeeRole->id,
            'department_id' => $supervisor2->department_id,
            'position_id' => $positions->where('hierarchy_level', 10)->first()->id,
            'supervisor_id' => $supervisor2->id,
            'phone_number' => '+95-9-123-456-780',
            'is_active' => true,
        ]);

        // Customer Service Staff (Report to Supervisors)
        User::create([
            'name' => 'Su Su Hlaing',
            'email' => 'susuhlaing@company.com',
            'password' => Hash::make('password'),
            'role_id' => $employeeRole->id,
            'department_id' => $supervisor3->department_id,
            'position_id' => $positions->where('hierarchy_level', 11)->first()->id,
            'supervisor_id' => $supervisor3->id,
            'phone_number' => '+95-9-234-567-891',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Phyo Wai Yan',
            'email' => 'phyowaiyan@company.com',
            'password' => Hash::make('password'),
            'role_id' => $employeeRole->id,
            'department_id' => $supervisor3->department_id,
            'position_id' => $positions->where('hierarchy_level', 11)->first()->id,
            'supervisor_id' => $supervisor3->id,
            'phone_number' => '+95-9-345-678-902',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Aye Aye Khaing',
            'email' => 'ayeayekhaing@company.com',
            'password' => Hash::make('password'),
            'role_id' => $employeeRole->id,
            'department_id' => $supervisor3->department_id,
            'position_id' => $positions->where('hierarchy_level', 11)->first()->id,
            'supervisor_id' => $supervisor3->id,
            'phone_number' => '+95-9-456-789-013',
            'is_active' => true,
        ]);
    }
}
