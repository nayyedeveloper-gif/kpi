<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;

class RoleManagement extends Component
{
    use WithPagination;

    public $name;
    public $display_name;
    public $description;
    public $is_active = true;
    public $roleId;
    public $isEditing = false;
    public $showModal = false;
    public $showPermissionsModal = false;
    public $search = '';
    public $selectedPermissions = [];
    public $editingRoleForPermissions;

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'display_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->display_name = $role->display_name;
        $this->description = $role->description;
        $this->is_active = $role->is_active;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->rules['name'] = 'required|string|max:255|unique:roles,name,' . $this->roleId;
        }

        $this->validate();

        if ($this->isEditing) {
            $role = Role::findOrFail($this->roleId);
            $role->update([
                'name' => $this->name,
                'display_name' => $this->display_name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Role updated successfully.');
        } else {
            Role::create([
                'name' => $this->name,
                'display_name' => $this->display_name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Role created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        
        // Check if role has users
        if ($role->users()->count() > 0) {
            session()->flash('error', 'Cannot delete role with assigned users.');
            return;
        }
        
        $role->delete();
        session()->flash('message', 'Role deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $role = Role::findOrFail($id);
        $role->update(['is_active' => !$role->is_active]);
        session()->flash('message', 'Role status updated.');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->display_name = '';
        $this->description = '';
        $this->is_active = true;
        $this->roleId = null;
        $this->resetValidation();
    }

    public function openPermissionsModal($roleId)
    {
        $this->editingRoleForPermissions = Role::with('permissions')->findOrFail($roleId);
        $this->selectedPermissions = $this->editingRoleForPermissions->permissions->pluck('id')->toArray();
        $this->showPermissionsModal = true;
    }

    public function updatePermissions()
    {
        if (!$this->editingRoleForPermissions) {
            return;
        }

        $this->editingRoleForPermissions->permissions()->sync($this->selectedPermissions);
        
        session()->flash('message', 'Permissions updated successfully.');
        $this->showPermissionsModal = false;
        $this->selectedPermissions = [];
        $this->editingRoleForPermissions = null;
    }

    public function closePermissionsModal()
    {
        $this->showPermissionsModal = false;
        $this->selectedPermissions = [];
        $this->editingRoleForPermissions = null;
    }

    public function getPermissionsProperty()
    {
        return Permission::orderBy('category')->orderBy('display_name')->get()->groupBy('category');
    }

    public function render()
    {
        $roles = Role::query()
            ->withCount(['users', 'permissions'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('display_name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.role-management', [
            'roles' => $roles,
            'permissions' => $this->permissions,
        ])->layout('layouts.app');
    }
}
