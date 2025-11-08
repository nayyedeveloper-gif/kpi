<?php

namespace App\Livewire;

use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentManagement extends Component
{
    use WithPagination;

    public $name;
    public $description;
    public $is_active = true;
    public $departmentId;
    public $isEditing = false;
    public $showModal = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
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
        $department = Department::findOrFail($id);
        $this->departmentId = $department->id;
        $this->name = $department->name;
        $this->description = $department->description;
        $this->is_active = $department->is_active;
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
        $this->validate();

        if ($this->isEditing) {
            $department = Department::findOrFail($this->departmentId);
            $department->update([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Department updated successfully.');
        } else {
            Department::create([
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Department created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        session()->flash('message', 'Department deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $department = Department::findOrFail($id);
        $department->update(['is_active' => !$department->is_active]);
        session()->flash('message', 'Department status updated.');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
        $this->departmentId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $departments = Department::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.department-management', [
            'departments' => $departments,
        ])->layout('layouts.app');
    }
}
