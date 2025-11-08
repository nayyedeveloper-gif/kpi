<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedDepartment = '';
    public $selectedPosition = '';
    public $showForm = false;
    public $showDetailsModal = false;
    public $editingUser = null;
    public $viewingUser = null;
    public $userDetails = [];

    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $phone_number = '';
    public $role_id = '';
    public $department_id = '';
    public $position_id = '';
    public $supervisor_id = '';
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'phone_number' => 'nullable|string|max:20',
        'role_id' => 'required|exists:roles,id',
        'department_id' => 'required|exists:departments,id',
        'position_id' => 'required|exists:positions,id',
        'supervisor_id' => 'nullable|exists:users,id',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedDepartment()
    {
        $this->resetPage();
    }

    public function updatedSelectedPosition()
    {
        $this->resetPage();
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function showEditForm($userId)
    {
        $user = User::findOrFail($userId);
        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->phone_number = $user->phone_number;
        $this->role_id = $user->role_id;
        $this->department_id = $user->department_id;
        $this->position_id = $user->position_id;
        $this->supervisor_id = $user->supervisor_id;
        $this->is_active = $user->is_active;
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->editingUser = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->phone_number = '';
        $this->role_id = '';
        $this->department_id = '';
        $this->position_id = '';
        $this->supervisor_id = '';
        $this->is_active = true;
        $this->showForm = false;
    }

    public function save()
    {
        if ($this->editingUser) {
            $this->rules['email'] = 'required|email|unique:users,email,' . $this->editingUser->id;
            $this->rules['password'] = 'nullable|string|min:8';
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'role_id' => $this->role_id,
            'department_id' => $this->department_id,
            'position_id' => $this->position_id,
            'supervisor_id' => $this->supervisor_id ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->editingUser) {
            $this->editingUser->update($data);
            session()->flash('message', 'User updated successfully!');
        } else {
            User::create($data);
            session()->flash('message', 'User created successfully!');
        }

        $this->resetForm();
    }

    public function delete($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();
        session()->flash('message', 'User deleted successfully!');
    }

    public function viewDetails($userId)
    {
        $this->viewingUser = User::with([
            'department', 
            'position', 
            'role', 
            'supervisor',
            'subordinates.position',
            'kpiMeasurements' => function ($query) {
                $query->latest()->limit(30);
            },
            'kpiLogs' => function ($query) {
                $query->latest()->limit(20);
            }
        ])->findOrFail($userId);

        // Calculate user statistics
        $measurements = $this->viewingUser->kpiMeasurements;
        
        $this->userDetails = [
            'total_measurements' => $measurements->count(),
            'avg_score' => $measurements->count() > 0 ? round($measurements->avg('total_score'), 2) : 0,
            'avg_percentage' => $measurements->count() > 0 ? round($measurements->avg(function ($m) {
                return $m->percentage;
            }), 2) : 0,
            'good_logs' => $this->viewingUser->kpiLogs()->where('status', 'good')->count(),
            'bad_logs' => $this->viewingUser->kpiLogs()->where('status', 'bad')->count(),
            'subordinates_count' => $this->viewingUser->subordinates->count(),
            'recent_measurements' => $measurements->take(10),
            'recent_logs' => $this->viewingUser->kpiLogs,
        ];

        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->viewingUser = null;
        $this->userDetails = [];
    }

    public function getUsersProperty()
    {
        return User::with(['department', 'position', 'supervisor', 'role'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedDepartment, function ($query) {
                $query->where('department_id', $this->selectedDepartment);
            })
            ->when($this->selectedPosition, function ($query) {
                $query->where('position_id', $this->selectedPosition);
            })
            ->paginate(10);
    }

    public function getDepartmentsProperty()
    {
        return Department::where('is_active', true)->orderBy('name')->get();
    }

    public function getPositionsProperty()
    {
        return Position::where('is_active', true)->orderBy('hierarchy_level')->get();
    }

    public function getSupervisorsProperty()
    {
        return User::where('id', '!=', $this->editingUser?->id)
            ->where('is_active', true)
            ->with(['position'])
            ->get();
    }

    public function getRolesProperty()
    {
        return Role::where('is_active', true)->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.user-management', [
            'users' => $this->users,
            'departments' => $this->departments,
            'positions' => $this->positions,
            'supervisors' => $this->supervisors,
            'roles' => $this->roles,
        ])->layout('layouts.app');
    }
}