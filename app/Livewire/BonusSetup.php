<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BonusTier;

class BonusSetup extends Component
{
    public $showModal = false;
    public $editMode = false;
    public $tierId;
    
    public $name;
    public $type = 'revenue';
    public $threshold;
    public $bonus_amount;
    public $bonus_percentage;
    public $calculation_method = 'fixed';
    public $priority = 0;
    public $is_active = true;
    public $description;
    
    public $selectedType = 'revenue';

    protected $rules = [
        'name' => 'required|string|max:255|min:3',
        'type' => 'required|in:revenue,quantity,commission',
        'threshold' => 'required|numeric|min:0|max:1000000000',
        'bonus_amount' => 'nullable|numeric|min:0|max:100000000',
        'bonus_percentage' => 'nullable|numeric|min:0|max:100',
        'calculation_method' => 'required|in:fixed,percentage,cumulative',
        'priority' => 'nullable|integer|min:0|max:100',
        'is_active' => 'boolean',
        'description' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'name.min' => 'Tier name must be at least 3 characters.',
        'threshold.max' => 'Threshold cannot exceed 1,000,000,000.00 MMK.',
        'bonus_amount.max' => 'Bonus amount cannot exceed 100,000,000.00 MMK.',
        'priority.max' => 'Priority must be between 0 and 100.',
    ];

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $tier = BonusTier::findOrFail($id);
        $this->tierId = $tier->id;
        $this->name = $tier->name;
        $this->type = $tier->type;
        $this->threshold = $tier->threshold;
        $this->bonus_amount = $tier->bonus_amount;
        $this->bonus_percentage = $tier->bonus_percentage;
        $this->calculation_method = $tier->calculation_method;
        $this->priority = $tier->priority;
        $this->is_active = $tier->is_active;
        $this->description = $tier->description;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'threshold' => $this->threshold,
            'bonus_amount' => $this->bonus_amount ?? 0,
            'bonus_percentage' => $this->bonus_percentage ?? 0,
            'calculation_method' => $this->calculation_method,
            'priority' => $this->priority ?? 0,
            'is_active' => $this->is_active,
            'description' => $this->description,
        ];

        if ($this->editMode) {
            BonusTier::find($this->tierId)->update($data);
            session()->flash('message', 'Bonus tier updated successfully!');
        } else {
            BonusTier::create($data);
            session()->flash('message', 'Bonus tier created successfully!');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        BonusTier::find($id)->delete();
        session()->flash('message', 'Bonus tier deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $tier = BonusTier::find($id);
        $tier->update(['is_active' => !$tier->is_active]);
        session()->flash('message', 'Status updated successfully!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->tierId = null;
        $this->name = '';
        $this->type = 'revenue';
        $this->threshold = null;
        $this->bonus_amount = null;
        $this->bonus_percentage = null;
        $this->calculation_method = 'fixed';
        $this->priority = 0;
        $this->is_active = true;
        $this->description = '';
        $this->resetErrorBag();
    }

    public function switchType($type)
    {
        $this->selectedType = $type;
    }

    public function render()
    {
        $revenueTiers = BonusTier::byType('revenue')->ordered()->get();
        $quantityTiers = BonusTier::byType('quantity')->ordered()->get();
        $commissionTiers = BonusTier::byType('commission')->ordered()->get();

        return view('livewire.bonus-setup', [
            'revenueTiers' => $revenueTiers,
            'quantityTiers' => $quantityTiers,
            'commissionTiers' => $commissionTiers,
        ])->layout('layouts.app');
    }
}
