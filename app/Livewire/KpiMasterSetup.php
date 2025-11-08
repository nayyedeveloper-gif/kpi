<?php

namespace App\Livewire;

use App\Models\KpiConfiguration;
use App\Models\Role;
use Livewire\Component;

class KpiMasterSetup extends Component
{
    public $configurations;
    public $roles;
    
    // Modal state
    public $showModal = false;
    public $editMode = false;
    public $configId;
    
    // Form fields
    public $name;
    public $level_type = 'operation';
    public $checker_role_id;
    public $target_role_id;
    public $cascade_enabled = true;
    public $max_cascade_levels = 5;
    public $is_active = true;
    public $description;
    
    // Impact weights
    public $good_impact_0 = 10.0;
    public $good_impact_1 = 3.0;
    public $good_impact_2 = 2.0;
    public $good_impact_3 = 1.0;
    public $good_impact_4 = 0.5;
    
    public $bad_impact_0 = -10.0;
    public $bad_impact_1 = -5.0;
    public $bad_impact_2 = -3.0;
    public $bad_impact_3 = -2.0;
    public $bad_impact_4 = -1.0;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->configurations = KpiConfiguration::with(['checkerRole', 'targetRole'])->get();
        $this->roles = Role::all();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $config = KpiConfiguration::findOrFail($id);
        
        $this->configId = $config->id;
        $this->name = $config->name;
        $this->level_type = $config->level_type;
        $this->checker_role_id = $config->checker_role_id;
        $this->target_role_id = $config->target_role_id;
        $this->cascade_enabled = $config->cascade_enabled;
        $this->max_cascade_levels = $config->max_cascade_levels;
        $this->is_active = $config->is_active;
        $this->description = $config->description;
        
        // Load impact weights
        $goodImpact = $config->good_impact ?? KpiConfiguration::getDefaultGoodImpact();
        $badImpact = $config->bad_impact ?? KpiConfiguration::getDefaultBadImpact();
        
        for ($i = 0; $i <= 4; $i++) {
            $this->{"good_impact_$i"} = $goodImpact[$i] ?? 0;
            $this->{"bad_impact_$i"} = $badImpact[$i] ?? 0;
        }
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'level_type' => 'required|in:operation,entry',
            'checker_role_id' => 'required|exists:roles,id',
            'target_role_id' => 'required|exists:roles,id',
            'max_cascade_levels' => 'required|integer|min:1|max:10',
        ]);

        $goodImpact = [
            0 => $this->good_impact_0,
            1 => $this->good_impact_1,
            2 => $this->good_impact_2,
            3 => $this->good_impact_3,
            4 => $this->good_impact_4,
        ];

        $badImpact = [
            0 => $this->bad_impact_0,
            1 => $this->bad_impact_1,
            2 => $this->bad_impact_2,
            3 => $this->bad_impact_3,
            4 => $this->bad_impact_4,
        ];

        $data = [
            'name' => $this->name,
            'level_type' => $this->level_type,
            'checker_role_id' => $this->checker_role_id,
            'target_role_id' => $this->target_role_id,
            'cascade_enabled' => $this->cascade_enabled,
            'max_cascade_levels' => $this->max_cascade_levels,
            'is_active' => $this->is_active,
            'description' => $this->description,
            'good_impact' => $goodImpact,
            'bad_impact' => $badImpact,
        ];

        if ($this->editMode) {
            KpiConfiguration::findOrFail($this->configId)->update($data);
            session()->flash('message', 'Configuration updated successfully!');
        } else {
            KpiConfiguration::create($data);
            session()->flash('message', 'Configuration created successfully!');
        }

        $this->closeModal();
        $this->loadData();
    }

    public function delete($id)
    {
        KpiConfiguration::findOrFail($id)->delete();
        session()->flash('message', 'Configuration deleted successfully!');
        $this->loadData();
    }

    public function toggleActive($id)
    {
        $config = KpiConfiguration::findOrFail($id);
        $config->update(['is_active' => !$config->is_active]);
        $this->loadData();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->configId = null;
        $this->name = '';
        $this->level_type = 'operation';
        $this->checker_role_id = null;
        $this->target_role_id = null;
        $this->cascade_enabled = true;
        $this->max_cascade_levels = 5;
        $this->is_active = true;
        $this->description = '';
        
        // Reset to defaults
        $this->good_impact_0 = 10.0;
        $this->good_impact_1 = 3.0;
        $this->good_impact_2 = 2.0;
        $this->good_impact_3 = 1.0;
        $this->good_impact_4 = 0.5;
        
        $this->bad_impact_0 = -10.0;
        $this->bad_impact_1 = -5.0;
        $this->bad_impact_2 = -3.0;
        $this->bad_impact_3 = -2.0;
        $this->bad_impact_4 = -1.0;
        
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.kpi-master-setup')->layout('layouts.app');
    }
}
