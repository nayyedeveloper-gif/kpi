<?php

namespace App\Livewire;

use App\Models\KpiMeasurement;
use App\Models\KpiLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KpiTracking extends Component
{
    use WithFileUploads;

    public $selectedDate;
    public $selectedUser;
    public $measurement;
    public $users;
    public $showLogModal = false;
    public $showDetailsModal = false;
    public $selectedKpi;
    
    // Quick log fields
    public $logStatus = 'good';
    public $logNotes = '';
    public $logPhoto;
    
    // View
    public $viewDate;
    public $dailyLogs = [];

    protected $rules = [
        'selectedUser' => 'required|exists:users,id',
        'selectedDate' => 'required|date',
        'logStatus' => 'required|in:good,bad',
        'logNotes' => 'nullable|string|max:500',
        'logPhoto' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->viewDate = Carbon::today()->format('Y-m-d');
        $this->users = User::active()->with(['department', 'position'])->get();
        $this->selectedUser = Auth::id();
        $this->loadExistingMeasurement();
    }

    public function updatedSelectedUser()
    {
        $this->loadExistingMeasurement();
    }

    public function updatedSelectedDate()
    {
        $this->loadExistingMeasurement();
    }

    public function loadExistingMeasurement()
    {
        if ($this->selectedUser && $this->selectedDate) {
            $this->measurement = KpiMeasurement::where('user_id', $this->selectedUser)
                ->whereDate('measurement_date', $this->selectedDate)
                ->first();

            if (!$this->measurement) {
                $this->measurement = KpiMeasurement::create([
                    'user_id' => $this->selectedUser,
                    'measurement_date' => $this->selectedDate,
                    'ready_to_sale' => false,
                    'counter_check' => false,
                    'cleanliness' => false,
                    'stock_check' => false,
                    'order_handling' => false,
                    'customer_followup' => false,
                ]);
            }
        }
    }

    public function openLogModal($kpiField)
    {
        $this->selectedKpi = $kpiField;
        $this->logStatus = 'good';
        $this->logNotes = '';
        $this->logPhoto = null;
        $this->showLogModal = true;
    }

    public function saveLog()
    {
        $this->validate([
            'logStatus' => 'required|in:good,bad',
            'logNotes' => 'nullable|string|max:500',
            'logPhoto' => 'nullable|image|max:2048',
        ]);

        if (!$this->measurement) {
            $this->loadExistingMeasurement();
        }

        $photoPath = null;
        if ($this->logPhoto) {
            $photoPath = $this->logPhoto->store('kpi-photos', 'public');
        }

        KpiLog::create([
            'kpi_measurement_id' => $this->measurement->id,
            'user_id' => Auth::id(),
            'kpi_field' => $this->selectedKpi,
            'status' => $this->logStatus,
            'notes' => $this->logNotes,
            'photo_path' => $photoPath,
            'logged_at' => now(),
        ]);

        // Update measurement field
        $this->measurement->update([
            $this->selectedKpi => ($this->logStatus === 'good'),
        ]);

        session()->flash('message', 'Log saved successfully!');
        $this->showLogModal = false;
        $this->loadExistingMeasurement();
    }

    public function viewDetails($date = null)
    {
        $this->viewDate = $date ?? $this->selectedDate;
        
        if ($this->measurement) {
            $this->dailyLogs = KpiLog::where('kpi_measurement_id', $this->measurement->id)
                ->whereDate('logged_at', $this->viewDate)
                ->with('user')
                ->orderBy('logged_at', 'desc')
                ->get();
        }
        
        $this->showDetailsModal = true;
    }

    public function closeModals()
    {
        $this->showLogModal = false;
        $this->showDetailsModal = false;
    }

    public function getKpiMeasurementsProperty()
    {
        return KpiMeasurement::with(['user', 'logs'])
            ->whereDate('measurement_date', $this->selectedDate)
            ->when($this->selectedUser, function ($query) {
                $query->where('user_id', $this->selectedUser);
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.kpi-tracking', [
            'kpiMeasurements' => $this->kpiMeasurements,
        ])->layout('layouts.app');
    }
}