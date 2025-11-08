<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\User;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class BranchManagement extends Component
{
    use WithPagination;

    public $name;
    public $code;
    public $address;
    public $phone;
    public $email;
    public $manager_id;
    public $department_id;
    public $is_active = true;
    public $branchId;
    public $isEditing = false;
    public $showModal = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:branches,code',
        'address' => 'nullable|string',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'manager_id' => 'nullable|exists:users,id',
        'department_id' => 'nullable|exists:departments,id',
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
        $branch = Branch::findOrFail($id);
        $this->branchId = $branch->id;
        $this->name = $branch->name;
        $this->code = $branch->code;
        $this->address = $branch->address;
        $this->phone = $branch->phone;
        $this->email = $branch->email;
        $this->manager_id = $branch->manager_id;
        $this->department_id = $branch->department_id;
        $this->is_active = $branch->is_active;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->rules['code'] = 'required|string|max:50|unique:branches,code,' . $this->branchId;
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'manager_id' => $this->manager_id,
            'department_id' => $this->department_id,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            Branch::find($this->branchId)->update($data);
            session()->flash('message', 'Branch updated successfully.');
        } else {
            Branch::create($data);
            session()->flash('message', 'Branch created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $branch = Branch::findOrFail($id);
        
        // Check if branch has users or departments
        if ($branch->users()->count() > 0 || $branch->departments()->count() > 0) {
            session()->flash('error', 'Cannot delete branch with existing users or departments.');
            return;
        }
        
        $branch->delete();
        session()->flash('message', 'Branch deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->update(['is_active' => !$branch->is_active]);
        session()->flash('message', 'Branch status updated.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->code = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
        $this->manager_id = null;
        $this->department_id = null;
        $this->is_active = true;
        $this->branchId = null;
        $this->resetValidation();
    }

    public function getManagersProperty()
    {
        return User::active()->orderBy('name')->get();
    }

    public function getDepartmentsProperty()
    {
        return Department::active()->orderBy('name')->get();
    }

    public function render()
    {
        $branches = Branch::query()
            ->with(['manager', 'department', 'users', 'departments'])
            ->withCount(['users', 'departments'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.branch-management', [
            'branches' => $branches,
            'managers' => $this->managers,
            'departments' => $this->departments,
        ])->layout('layouts.app');
    }
}
