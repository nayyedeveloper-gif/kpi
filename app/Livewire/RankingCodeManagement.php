<?php

namespace App\Livewire;

use App\Models\RankingCode;
use App\Models\Branch;
use App\Models\Group;
use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;

class RankingCodeManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editMode = false;
    public $rankingCodeId;
    
    public $branch_name = '';
    public $group_name = '';
    public $position_name = '';
    public $name = '';
    public $guardian_name = '';
    public $id_code = '';
    public $ranking_id = '';
    
    public $search = '';

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'branch_name' => 'required|string|max:255',
        'group_name' => 'required|string|max:255',
        'position_name' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'guardian_name' => 'nullable|string|max:255',
        'id_code' => 'required|string|max:50',
        'ranking_id' => 'required|string|max:255|unique:ranking_codes,ranking_id',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $rankingCode = RankingCode::findOrFail($id);
        $this->rankingCodeId = $rankingCode->id;
        $this->branch_name = $rankingCode->branch_name;
        $this->group_name = $rankingCode->group_name;
        $this->position_name = $rankingCode->position_name;
        $this->name = $rankingCode->name;
        $this->guardian_name = $rankingCode->guardian_name ?? '';
        $this->id_code = $rankingCode->id_code;
        $this->ranking_id = $rankingCode->ranking_id;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        if ($this->editMode) {
            $this->rules['ranking_id'] = 'required|string|max:255|unique:ranking_codes,ranking_id,' . $this->rankingCodeId;
        }
        
        $this->validate();

        $data = [
            'branch_name' => $this->branch_name,
            'group_name' => $this->group_name,
            'position_name' => $this->position_name,
            'name' => $this->name,
            'guardian_name' => $this->guardian_name ?: null,
            'id_code' => $this->id_code,
            'ranking_id' => $this->ranking_id,
        ];

        if ($this->editMode) {
            RankingCode::findOrFail($this->rankingCodeId)->update($data);
            session()->flash('message', 'Ranking code updated successfully.');
        } else {
            RankingCode::create($data);
            session()->flash('message', 'Ranking code created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        RankingCode::findOrFail($id)->delete();
        session()->flash('message', 'Ranking code deleted successfully.');
    }

    private function resetForm()
    {
        $this->branch_name = '';
        $this->group_name = '';
        $this->position_name = '';
        $this->name = '';
        $this->guardian_name = '';
        $this->id_code = '';
        $this->ranking_id = '';
        $this->rankingCodeId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $query = RankingCode::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('branch_name', 'like', '%' . $this->search . '%')
                  ->orWhere('group_name', 'like', '%' . $this->search . '%')
                  ->orWhere('position_name', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%')
                  ->orWhere('ranking_id', 'like', '%' . $this->search . '%')
                  ->orWhere('id_code', 'like', '%' . $this->search . '%');
            });
        }

        $rankingCodes = $query->latest()->paginate(15);
        
        $branches = Branch::active()->pluck('name')->unique()->sort()->values();
        $groups = Group::active()->pluck('name')->unique()->sort()->values();
        $positions = Position::active()->pluck('name')->unique()->sort()->values();

        return view('livewire.ranking-code-management', [
            'rankingCodes' => $rankingCodes,
            'branches' => $branches,
            'groups' => $groups,
            'positions' => $positions,
        ])->layout('layouts.app');
    }
}