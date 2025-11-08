<?php

namespace App\Livewire;

use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;

class PositionManagement extends Component
{
    use WithPagination;

    public $name;
    public $description;
    public $hierarchy_level = 1;
    public $is_active = true;
    public $positionId;
    public $isEditing = false;
    public $showModal = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'hierarchy_level' => 'required|integer|min:1|max:10',
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
        $position = Position::findOrFail($id);
        $this->positionId = $position->id;
        $this->name = $position->name;
        $this->description = $position->description;
        $this->hierarchy_level = $position->hierarchy_level;
        $this->is_active = $position->is_active;
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
            $position = Position::findOrFail($this->positionId);
            $position->update([
                'name' => $this->name,
                'description' => $this->description,
                'hierarchy_level' => $this->hierarchy_level,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Position updated successfully.');
        } else {
            Position::create([
                'name' => $this->name,
                'description' => $this->description,
                'hierarchy_level' => $this->hierarchy_level,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Position created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();
        session()->flash('message', 'Position deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $position = Position::findOrFail($id);
        $position->update(['is_active' => !$position->is_active]);
        session()->flash('message', 'Position status updated.');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->hierarchy_level = 1;
        $this->is_active = true;
        $this->positionId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $positions = Position::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderedByHierarchy()
            ->paginate(10);

        return view('livewire.position-management', [
            'positions' => $positions,
        ])->layout('layouts.app');
    }
}
