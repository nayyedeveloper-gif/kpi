<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\User;
use App\Models\Branch;
use Livewire\Component;
use Livewire\WithPagination;

class GroupManagement extends Component
{
    use WithPagination;

    public $name;
    public $code;
    public $description;
    public $group_type = 'team';
    public $leader_id;
    public $branch_id;
    public $is_active = true;
    public $groupId;
    public $isEditing = false;
    public $showModal = false;
    public $showMembersModal = false;
    public $search = '';
    public $filterType = '';
    public $filterBranch = '';
    
    // For member management
    public $editingGroup;
    public $selectedMembers = [];
    public $memberRoles = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:groups,code',
        'description' => 'nullable|string',
        'group_type' => 'required|in:team,project,committee',
        'leader_id' => 'nullable|exists:users,id',
        'branch_id' => 'nullable|exists:branches,id',
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
        $group = Group::findOrFail($id);
        $this->groupId = $group->id;
        $this->name = $group->name;
        $this->code = $group->code;
        $this->description = $group->description;
        $this->group_type = $group->group_type;
        $this->leader_id = $group->leader_id;
        $this->branch_id = $group->branch_id;
        $this->is_active = $group->is_active;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->rules['code'] = 'required|string|max:50|unique:groups,code,' . $this->groupId;
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'group_type' => $this->group_type,
            'leader_id' => $this->leader_id,
            'branch_id' => $this->branch_id,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing) {
            Group::find($this->groupId)->update($data);
            session()->flash('message', 'Group updated successfully.');
        } else {
            Group::create($data);
            session()->flash('message', 'Group created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();
        session()->flash('message', 'Group deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $group = Group::findOrFail($id);
        $group->update(['is_active' => !$group->is_active]);
        session()->flash('message', 'Group status updated.');
    }

    public function openMembersModal($groupId)
    {
        $this->editingGroup = Group::with('members')->findOrFail($groupId);
        $this->selectedMembers = $this->editingGroup->members->pluck('id')->toArray();
        
        // Initialize member roles
        foreach ($this->editingGroup->members as $member) {
            $this->memberRoles[$member->id] = $member->pivot->role_in_group;
        }
        
        $this->showMembersModal = true;
    }

    public function updateMembers()
    {
        if (!$this->editingGroup) {
            return;
        }

        // Prepare sync data with roles
        $syncData = [];
        foreach ($this->selectedMembers as $userId) {
            $syncData[$userId] = [
                'role_in_group' => $this->memberRoles[$userId] ?? 'member',
                'joined_at' => now(),
            ];
        }

        $this->editingGroup->members()->sync($syncData);
        
        session()->flash('message', 'Group members updated successfully.');
        $this->closeMembersModal();
    }

    public function closeMembersModal()
    {
        $this->showMembersModal = false;
        $this->selectedMembers = [];
        $this->memberRoles = [];
        $this->editingGroup = null;
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
        $this->description = '';
        $this->group_type = 'team';
        $this->leader_id = null;
        $this->branch_id = null;
        $this->is_active = true;
        $this->groupId = null;
        $this->resetValidation();
    }

    public function getLeadersProperty()
    {
        return User::active()->orderBy('name')->get();
    }

    public function getBranchesProperty()
    {
        return Branch::active()->orderBy('name')->get();
    }

    public function getAvailableUsersProperty()
    {
        return User::active()->orderBy('name')->get();
    }

    public function render()
    {
        $groups = Group::query()
            ->with(['leader', 'branch', 'members'])
            ->withCount('members')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterType, function ($query) {
                $query->where('group_type', $this->filterType);
            })
            ->when($this->filterBranch, function ($query) {
                $query->where('branch_id', $this->filterBranch);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.group-management', [
            'groups' => $groups,
            'leaders' => $this->leaders,
            'branches' => $this->branches,
            'availableUsers' => $this->availableUsers,
        ])->layout('layouts.app');
    }
}
