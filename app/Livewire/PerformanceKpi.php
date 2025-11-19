<?php

namespace App\Livewire;

use App\Models\KpiMeasurement;
use App\Models\KpiLog;
use App\Models\User;
use App\Models\RankingCode;
use Livewire\Component;
use Livewire\WithPagination;

class PerformanceKpi extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedDate = '';
    public $selectedUser = '';
    public $showDetailsModal = false;
    public $selectedMeasurement = null;

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getKpiMeasurementsProperty()
    {
        return KpiMeasurement::with(['rankingCode', 'user'])
            ->when($this->search, function ($query) {
                $query->whereHas('rankingCode', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('ranking_id', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedDate, function ($query) {
                $query->whereDate('measurement_date', $this->selectedDate);
            })
            ->when($this->selectedUser, function ($query) {
                $query->whereHas('rankingCode', function ($q) {
                    $q->where('id', $this->selectedUser);
                });
            })
            ->orderBy('measurement_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function viewDetails($measurementId)
    {
        $this->selectedMeasurement = KpiMeasurement::with(['rankingCode', 'logs', 'user'])->find($measurementId);
        
        // Ensure KPI arrays are properly decoded
        if ($this->selectedMeasurement) {
            $this->selectedMeasurement->personality_kpis = is_array($this->selectedMeasurement->personality_kpis) 
                ? $this->selectedMeasurement->personality_kpis 
                : (is_string($this->selectedMeasurement->personality_kpis) 
                    ? json_decode($this->selectedMeasurement->personality_kpis, true) 
                    : []);
            
            $this->selectedMeasurement->team_management_kpis = is_array($this->selectedMeasurement->team_management_kpis) 
                ? $this->selectedMeasurement->team_management_kpis 
                : (is_string($this->selectedMeasurement->team_management_kpis) 
                    ? json_decode($this->selectedMeasurement->team_management_kpis, true) 
                    : []);
            
            $this->selectedMeasurement->customer_follow_up_kpis = is_array($this->selectedMeasurement->customer_follow_up_kpis) 
                ? $this->selectedMeasurement->customer_follow_up_kpis 
                : (is_string($this->selectedMeasurement->customer_follow_up_kpis) 
                    ? json_decode($this->selectedMeasurement->customer_follow_up_kpis, true) 
                    : []);
            
            $this->selectedMeasurement->supervised_level_kpis = is_array($this->selectedMeasurement->supervised_level_kpis) 
                ? $this->selectedMeasurement->supervised_level_kpis 
                : (is_string($this->selectedMeasurement->supervised_level_kpis) 
                    ? json_decode($this->selectedMeasurement->supervised_level_kpis, true) 
                    : []);
            
            // Keep old arrays for backward compatibility
            $this->selectedMeasurement->performance_kpis = $this->selectedMeasurement->team_management_kpis;
            $this->selectedMeasurement->hospitality_kpis = $this->selectedMeasurement->team_management_kpis;
        }
        
        $this->showDetailsModal = true;
    }

    public function deleteMeasurement($measurementId)
    {
        $measurement = KpiMeasurement::find($measurementId);
        if ($measurement) {
            // Delete associated logs first
            $measurement->logs()->delete();
            // Then delete the measurement
            $measurement->delete();
            
            session()->flash('message', 'KPI measurement deleted successfully!');
        }
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedMeasurement = null;
    }

    public function getRankingCodesProperty()
    {
        return RankingCode::pluck('name', 'id');
    }

    public function render()
    {
        return view('livewire.performance-kpi', [
            'kpiMeasurements' => $this->kpiMeasurements,
            'users' => $this->rankingCodes,
        ]);
    }
}
