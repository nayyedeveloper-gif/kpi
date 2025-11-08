<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use App\Models\Branch;
use App\Models\Group;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class OrganizationChart extends Component
{
    public $searchTerm = '';
    public $selectedDepartment = '';
    public $selectedBranch = '';
    public $viewType = 'hierarchy';
    public $departments;
    public $positions;
    public $roles;
    public $branches;
    
    // Edit User Modal
    public $showEditModal = false;
    public $editUserId;
    public $editName;
    public $editEmail;
    public $editDepartmentId;
    public $editPositionId;
    public $editSupervisorId;
    public $editIsActive;
    
    // Add Subordinate Modal
    public $showAddModal = false;
    public $addSupervisorId;
    public $addName;
    public $addEmail;
    public $addPassword;
    public $addDepartmentId;
    public $addPositionId;
    
    // Performance Modal
    public $showPerformanceModal = false;
    public $performanceUser;
    public $performanceData;
    public $teamPerformanceData;

    public function mount()
    {
        $this->departments = Department::active()->get();
        $this->positions = Position::orderBy('hierarchy_level')->get();
        $this->roles = Role::all();
        $this->branches = Branch::active()->get();
    }

    public function switchView($type)
    {
        $this->viewType = $type;
    }

    public function updatedSearchTerm()
    {
        // Trigger re-render when search term changes
    }

    public function updatedSelectedDepartment()
    {
        // Trigger re-render when department changes
    }

    public function getOrganizationDataProperty()
    {
        // If search or filter is active, show filtered results
        if ($this->searchTerm || $this->selectedDepartment) {
            return $this->getFilteredUsers();
        }

        // Return data based on view type
        if ($this->viewType === 'hierarchy') {
            $tree = User::getOrganizationTree();
            
            // If no top-level users, show all users in grid format
            if ($tree->isEmpty()) {
                return User::with(['position', 'department', 'role', 'supervisor'])
                    ->active()
                    ->get()
                    ->map(function ($user) {
                        return $this->buildUserNode($user);
                    });
            }
            
            return $tree;
        }
        
        return collect(); // For group/branch views, data loaded separately
    }
    
    public function getGroupDataProperty()
    {
        $query = Group::with(['leader', 'members.position', 'members.department', 'branch'])
            ->active();
        
        if ($this->selectedBranch) {
            $query->where('branch_id', $this->selectedBranch);
        }
        
        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }
        
        return $query->get()->map(function ($group) {
            return $this->buildGroupData($group);
        });
    }
    
    public function getBranchDataProperty()
    {
        $query = Branch::with(['groups.leader', 'groups.members'])
            ->active();
        
        if ($this->searchTerm) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }
        
        return $query->get()->map(function ($branch) {
            return $this->buildBranchData($branch);
        });
    }
    
    private function buildGroupData($group)
    {
        $members = $group->members;
        $performance = $members->map(function ($member) {
            return $member->getCurrentPerformance();
        })->filter();
        
        return [
            'id' => $group->id,
            'name' => $group->name,
            'description' => $group->description,
            'branch_name' => $group->branch->name ?? 'N/A',
            'leader' => [
                'id' => $group->leader->id ?? null,
                'name' => $group->leader->name ?? 'No Leader',
                'position' => $group->leader->position->name ?? 'N/A',
                'photo' => $group->leader->profile_photo ?? null,
            ],
            'members' => $members->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'position' => $member->position->name ?? 'N/A',
                    'department' => $member->department->name ?? 'N/A',
                    'email' => $member->email,
                    'photo' => $member->profile_photo,
                    'is_active' => $member->is_active,
                ];
            }),
            'member_count' => $members->count(),
            'avg_performance' => $performance->avg('overall_score') ?? 0,
            'total_kpis' => $performance->sum('kpis_completed') ?? 0,
            'created_at' => $group->created_at->format('M d, Y'),
        ];
    }
    
    private function buildBranchData($branch)
    {
        $groups = $branch->groups;
        $allMembers = $groups->flatMap(function ($group) {
            return $group->members;
        });
        
        return [
            'id' => $branch->id,
            'name' => $branch->name,
            'location' => $branch->location,
            'groups' => $groups->map(function ($group) {
                return $this->buildGroupData($group);
            }),
            'group_count' => $groups->count(),
            'total_members' => $allMembers->count(),
        ];
    }

    private function getFilteredUsers()
    {
        $query = User::with(['position', 'department', 'role', 'supervisor'])
            ->active();

        // Apply search filter - search across all users
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('position', function ($q) {
                      $q->where('name', 'like', '%' . $this->searchTerm . '%');
                  })
                  ->orWhereHas('department', function ($q) {
                      $q->where('name', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        }

        // Apply department filter
        if ($this->selectedDepartment) {
            $query->where('department_id', $this->selectedDepartment);
        }

        $users = $query->get();

        // Build hierarchy data for filtered users
        return $users->map(function ($user) {
            return $this->buildUserNode($user);
        });
    }

    private function buildUserNode($user)
    {
        $performance = $user->getCurrentPerformance();
        
        return [
            'id' => $user->id,
            'name' => $user->name,
            'title' => $user->position?->name ?? 'No Position',
            'department' => $user->department?->name ?? 'No Department',
            'role' => $user->role?->display_name ?? 'No Role',
            'email' => $user->email,
            'phone' => $user->phone_number,
            'supervisor_id' => $user->supervisor_id,
            'supervisor_name' => $user->supervisor?->name ?? null,
            'is_active' => $user->is_active,
            'performance_score' => $performance->overall_score ?? 0,
            'subordinates' => [],
        ];
    }

    public function openEditModal($userId)
    {
        $user = User::with(['department', 'position', 'supervisor'])->findOrFail($userId);
        
        $this->editUserId = $user->id;
        $this->editName = $user->name;
        $this->editEmail = $user->email;
        $this->editDepartmentId = $user->department_id;
        $this->editPositionId = $user->position_id;
        $this->editSupervisorId = $user->supervisor_id;
        $this->editIsActive = $user->is_active;
        
        $this->showEditModal = true;
    }
    
    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetValidation();
    }
    
    public function updateUser()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editEmail' => 'required|email|max:255|unique:users,email,' . $this->editUserId,
            'editDepartmentId' => 'required|exists:departments,id',
            'editPositionId' => 'required|exists:positions,id',
            'editSupervisorId' => 'nullable|exists:users,id',
        ]);
        
        $user = User::findOrFail($this->editUserId);
        $user->update([
            'name' => $this->editName,
            'email' => $this->editEmail,
            'department_id' => $this->editDepartmentId,
            'position_id' => $this->editPositionId,
            'supervisor_id' => $this->editSupervisorId,
            'is_active' => $this->editIsActive,
        ]);
        
        $this->closeEditModal();
        session()->flash('message', 'User updated successfully!');
        $this->dispatch('userUpdated');
    }
    
    public function openAddModal($supervisorId)
    {
        $this->addSupervisorId = $supervisorId;
        $this->addName = '';
        $this->addEmail = '';
        $this->addPassword = '';
        $this->addDepartmentId = '';
        $this->addPositionId = '';
        
        // Pre-fill department from supervisor
        $supervisor = User::find($supervisorId);
        if ($supervisor) {
            $this->addDepartmentId = $supervisor->department_id;
        }
        
        $this->showAddModal = true;
    }
    
    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->resetValidation();
    }
    
    public function addSubordinate()
    {
        $this->validate([
            'addName' => 'required|string|max:255',
            'addEmail' => 'required|email|max:255|unique:users,email',
            'addPassword' => 'required|string|min:8',
            'addDepartmentId' => 'required|exists:departments,id',
            'addPositionId' => 'required|exists:positions,id',
        ]);
        
        // Determine role based on position
        $position = Position::find($this->addPositionId);
        $roleId = $this->determineRoleId($position->name);
        
        User::create([
            'name' => $this->addName,
            'email' => $this->addEmail,
            'password' => Hash::make($this->addPassword),
            'department_id' => $this->addDepartmentId,
            'position_id' => $this->addPositionId,
            'supervisor_id' => $this->addSupervisorId,
            'role_id' => $roleId,
            'is_active' => true,
        ]);
        
        $this->closeAddModal();
        session()->flash('message', 'Subordinate added successfully!');
        $this->dispatch('userAdded');
    }
    
    private function determineRoleId($positionName)
    {
        $positionLower = strtolower($positionName);
        
        if (str_contains($positionLower, 'ceo') || str_contains($positionLower, 'chief executive')) {
            $role = Role::where('name', 'admin')->first();
        } elseif (str_contains($positionLower, 'manager') || str_contains($positionLower, 'director')) {
            $role = Role::where('name', 'manager')->first();
        } elseif (str_contains($positionLower, 'supervisor')) {
            $role = Role::where('name', 'supervisor')->first();
        } else {
            $role = Role::where('name', 'employee')->first();
        }
        
        return $role ? $role->id : Role::where('name', 'employee')->first()->id;
    }
    
    public function showPerformanceModal($userId)
    {
        $this->performanceUser = User::with(['department', 'position', 'role', 'supervisor'])
            ->findOrFail($userId);
        
        $this->performanceData = $this->performanceUser->getCurrentPerformance();
        $this->teamPerformanceData = $this->performanceUser->getTeamPerformance();
        
        $this->showPerformanceModal = true;
    }
    
    public function closePerformanceModal()
    {
        $this->showPerformanceModal = false;
    }

    public function render()
    {
        $data = [
            'organizationData' => $this->organizationData,
            'groupData' => $this->viewType === 'group' ? $this->groupData : collect(),
            'branchData' => $this->viewType === 'branch' ? $this->branchData : collect(),
        ];
        
        return view('livewire.organization-chart', $data)->layout('layouts.app');
    }
}