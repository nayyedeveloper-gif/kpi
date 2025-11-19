<?php

namespace App\Livewire;

use App\Models\KpiMeasurement;
use App\Models\KpiLog;
use App\Models\RankingCode;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class KpiTracking extends Component
{
    use WithFileUploads;

    public $selectedDate;
    public $selectedRankingCode;
    public $search = '';
    public $measurement;
    public $filteredRankingCodes;
    public $showSuggestions = false;
    public $searchSuggestions = [];
    public $selectedSuggestionIndex = -1;
    public $photo;
    public $activeTab = 'entry';
    
    // KPI Data - Simplified and Systematic
    public $personalityScore = '0/4';
    public $performanceScore = '0/3';
    public $hospitalityScore = '0/2';
    public $customerFollowUpScore = 10; // Default to 10
    public $numberOfPeople = 0;
    public $supervisedLevelScore = 5; // Default to 5
    public $kpiNotes = '';
    
    // KPI Arrays - Comprehensive structure from kpicheck.md
    public $personalityKpis = [
        'hair' => ['label' => 'ဆံပင်', 'checked' => false],
        'shirt' => ['label' => 'အင်္ကျီ', 'checked' => false],
        'appearance' => ['label' => 'မျက်နှာအသွင်အပြင်', 'checked' => false],
        'hygiene' => ['label' => 'အနံ့အသက်', 'checked' => false]
    ];
    
    public $teamManagementKpis = [
        // Performance (CS)
        'performance_in_out_log' => ['label' => 'လူဝင်/ထွက်စာရင်း', 'category' => 'Performance (CS)', 'checked' => false],
        'performance_service_food' => ['label' => 'Service Food စာရင်း', 'category' => 'Performance (CS)', 'checked' => false],
        
        // Hospitality (CS)
        'hospitality_3s' => ['label' => '3S (Sweet,Smile,Smart)', 'category' => 'Hospitality (CS)', 'checked' => false],
        'hospitality_greeting' => ['label' => 'မင်္ဂလာနှုတ်ခွန်းဆက်စကား', 'category' => 'Hospitality (CS)', 'checked' => false],
        
        // Cleaning (CS)
        'cleaning_floor_doors' => ['label' => 'ဆိုင်ကြမ်းခင်း/တံခါး', 'category' => 'Cleaning (CS)', 'checked' => false],
        
        // Learning (CS)
        'learning_calculation' => ['label' => 'ရတနာတွက်ချက်နည်းများ', 'category' => 'Learning (CS)', 'checked' => false],
        'learning_forms' => ['label' => 'Form အသုံးပြုနည်းများ', 'category' => 'Learning (CS)', 'checked' => false],
        'learning_procedures' => ['label' => 'လုပ်ငန်စဉ်များနှင့် ပူးပေါင်းဆောင်ရွက်ခြင်း', 'category' => 'Learning (CS)', 'checked' => false],
        
        // Ready to Sales (SR)
        'ready_to_sales_time' => ['label' => 'မနက် ၀၉း၃၀', 'category' => 'Ready to Sales (SR)', 'checked' => false],
        
        // Counter Check (SR)
        'counter_check_items' => ['label' => 'ဗန်း/မှန်/လက်အိတ်/မှန်ဘီလူး/လက်တိုင်းကွင်း/လက်တိုင်းတုတ်/Calculator/ဘောင်ချာစာအုပ်/ဘောပင်', 'category' => 'Counter Check (SR)', 'checked' => false],
        
        // Counter သန့်ရှင်းမှု (SR)
        'counter_cleaning_area' => ['label' => 'ကောင်တာဆက်စပ်ဧရိယာ (အရှေ့၊ အနောက်၊အတွင်း)', 'category' => 'Counter သန့်ရှင်းမှု (SR)', 'checked' => false],
        'counter_cleaning_lights' => ['label' => 'မီးသီး', 'category' => 'Counter သန့်ရှင်းမှု (SR)', 'checked' => false],
        
        // Display (SR)
        'display_full_display' => ['label' => 'Display တုံးအပြည့်', 'category' => 'Display (SR)', 'checked' => false],
        'display_full_barcode' => ['label' => 'Barcode အပြည့်', 'category' => 'Display (SR)', 'checked' => false],
        'display_no_extra' => ['label' => 'Display တုံး ၃တုံးအပြင် အပို မထားရ', 'category' => 'Display (SR)', 'checked' => false],
        'display_stock_knowledge' => ['label' => 'မိမိ၏ Stock အဝင်အထွက်ကို ၃ရက်စာ သိရန်။', 'category' => 'Display (SR)', 'checked' => false],
        
        // Voucher Report (SR)
        'voucher_report_update' => ['label' => 'Voucher ဖွင့်ပြီးတိုင်း Sales Adminသို့ Update ပို့ရန်', 'category' => 'Voucher Report (SR)', 'checked' => false],
        
        // Documents (SR)
        'documents_accuracy' => ['label' => 'Doucmentsများ မှန်ကန်အောင် ဆောင်ရွက်ရန်( ပြင်ဆင်/Order)', 'category' => 'Documents (SR)', 'checked' => false],
        
        // Daily Sales Summary Report (Mgr)
        'daily_sales_summary' => ['label' => 'Daily Sales Summary Report', 'category' => 'Daily Sales Summary Report (Mgr)', 'checked' => false],
        
        // Daily Sales Operation Report (Mgr)
        'daily_sales_operation' => ['label' => 'Daily Sales Operation Report', 'category' => 'Daily Sales Operation Report (Mgr)', 'checked' => false],
    ];
    
    public $customerFollowUpKpis = [
        'follow_up_schedule' => ['label' => '1 Day, 1 Week, 1 Month', 'checked' => false],
    ];
    
    public $supervisedLevelKpis = [
        'supervisor_marks' => ['label' => 'သက်ဆိုင်ရာ ကြီးကြပ်သူမှ ပေးသော အမှတ်', 'checked' => false],
    ];

    protected $rules = [
        'selectedRankingCode' => 'required|exists:ranking_codes,id',
        'selectedDate' => 'required|date',
        'customerFollowUpScore' => 'required|integer|min:0|max:10',
        'supervisedLevelScore' => 'required|integer|min:0|max:5',
        'numberOfPeople' => 'required|integer|min:0',
        'photo' => 'nullable|image|max:2048', // Max 2MB
    ];

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->selectedRankingCode = null;
        $this->loadFilteredRankingCodes();
        $this->updateScores();
    }
    
    public function updatedSearch()
    {
        $this->loadFilteredRankingCodes();
        $this->loadSearchSuggestions();
        
        // Show suggestions if search has content and we have suggestions
        $this->showSuggestions = !empty($this->search) && !empty($this->searchSuggestions);
    }
    
    public function selectSuggestion($suggestion)
    {
        $this->search = $suggestion;
        $this->showSuggestions = false;
        $this->loadFilteredRankingCodes();
    }
    
    public function hideSuggestions()
    {
        $this->showSuggestions = false;
    }
    
    public function updatedSelectedRankingCode()
    {
        // No need to load existing measurement since we allow multiple entries per day
    }

    public function loadFilteredRankingCodes()
    {
        $query = RankingCode::query();

        if ($this->search) {
            $searchTerm = strtolower($this->search);
            $query->where(function ($q) use ($searchTerm) {
                // Search in ranking code fields
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(ranking_id) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(guardian_name) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(position_name) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(group_name) LIKE ?', ['%' . $searchTerm . '%'])
                  // Search in associated users
                  ->orWhereHas('users', function ($userQuery) use ($searchTerm) {
                      $userQuery->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%'])
                               ->orWhereRaw('LOWER(email) LIKE ?', ['%' . $searchTerm . '%']);
                  });
            });
        }

        $this->filteredRankingCodes = $query->orderBy('name')->pluck('name', 'id');
    }

    public function loadSearchSuggestions()
    {
        if (empty($this->search) || strlen($this->search) < 2) {
            $this->searchSuggestions = [];
            return;
        }

        $searchTerm = strtolower($this->search);
        $suggestions = [];

        // Get ranking code suggestions
        $rankingCodeSuggestions = RankingCode::where(function ($query) use ($searchTerm) {
            $query->whereRaw('LOWER(name) LIKE ?', [$searchTerm . '%'])
                  ->orWhereRaw('LOWER(ranking_id) LIKE ?', [$searchTerm . '%'])
                  ->orWhereRaw('LOWER(guardian_name) LIKE ?', [$searchTerm . '%'])
                  ->orWhereRaw('LOWER(position_name) LIKE ?', [$searchTerm . '%'])
                  ->orWhereRaw('LOWER(group_name) LIKE ?', [$searchTerm . '%']);
        })
        ->limit(5)
        ->get(['name', 'ranking_id'])
        ->map(function ($item) {
            return [
                'text' => $item->name,
                'type' => 'ranking_code',
                'id' => $item->ranking_id
            ];
        });

        // Get user suggestions
        $userSuggestions = RankingCode::whereHas('users', function ($query) use ($searchTerm) {
            $query->whereRaw('LOWER(name) LIKE ?', [$searchTerm . '%'])
                  ->orWhereRaw('LOWER(email) LIKE ?', [$searchTerm . '%']);
        })
        ->with('users')
        ->limit(5)
        ->get()
        ->pluck('users')
        ->flatten()
        ->map(function ($user) {
            return [
                'text' => $user->name,
                'type' => 'user',
                'id' => $user->email
            ];
        });

        // Combine and limit to 8 total suggestions
        $suggestions = $rankingCodeSuggestions->concat($userSuggestions)->take(8);

        $this->searchSuggestions = $suggestions->toArray();
    }

    public function loadExistingMeasurement()
    {
        if ($this->selectedRankingCode && $this->selectedDate) {
            $this->measurement = KpiMeasurement::where('ranking_code_id', $this->selectedRankingCode)
                ->whereDate('measurement_date', $this->selectedDate)
                ->first();

            if ($this->measurement) {
                // Load existing data
                $this->personalityScore = $this->measurement->personality_score ?? '0/4';
                $this->performanceScore = $this->measurement->performance_score ?? '0/3';
                $this->hospitalityScore = $this->measurement->hospitality_score ?? '0/2';
                $this->customerFollowUpScore = $this->measurement->customer_follow_up_score ?? 0;
                $this->supervisedLevelScore = $this->measurement->supervised_level_score ?? 0;
                $this->numberOfPeople = $this->measurement->number_of_people ?? 0;
                $this->kpiNotes = $this->measurement->notes ?? '';
                $this->photo = null; // Reset photo input for editing

                // Load KPI arrays - ensure they are always arrays
                $personalityData = $this->measurement->personality_kpis;
                if (is_array($personalityData) && !empty($personalityData)) {
                    $this->personalityKpis = $personalityData;
                }

                $teamManagementData = $this->measurement->team_management_kpis ?? [];
                if (is_array($teamManagementData) && !empty($teamManagementData)) {
                    $this->teamManagementKpis = $teamManagementData;
                }

                $customerFollowUpData = $this->measurement->customer_follow_up_kpis ?? [];
                if (is_array($customerFollowUpData) && !empty($customerFollowUpData)) {
                    $this->customerFollowUpKpis = $customerFollowUpData;
                }

                $supervisedLevelData = $this->measurement->supervised_level_kpis ?? [];
                if (is_array($supervisedLevelData) && !empty($supervisedLevelData)) {
                    $this->supervisedLevelKpis = $supervisedLevelData;
                }
            } else {
                // Reset to defaults
                $this->resetForm();
            }
        }
        $this->updateScores();
    }

    public function updateScores()
    {
        $this->updatePersonalityScore();
        $this->updateTeamManagementScore();
    }
    
    protected function updatePersonalityScore()
    {
        $kpis = is_array($this->personalityKpis) ? $this->personalityKpis : [];
        $checkedCount = count(array_filter($kpis, fn($kpi) => isset($kpi['checked']) && $kpi['checked']));
        $this->personalityScore = "$checkedCount/4";
    }

    protected function updateTeamManagementScore()
    {
        $kpis = is_array($this->teamManagementKpis) ? $this->teamManagementKpis : [];
        $checkedCount = count(array_filter($kpis, fn($kpi) => isset($kpi['checked']) && $kpi['checked']));
        $totalCount = count($kpis);
        $this->performanceScore = "$checkedCount/$totalCount";
        $this->hospitalityScore = "$checkedCount/$totalCount"; // Using same score for now, can be customized later
    }

    public function submitForm()
    {
        $this->validate();

        // Handle photo upload
        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('kpi-photos', 'public');
        }

        $data = [
            'ranking_code_id' => $this->selectedRankingCode,
            'measurement_date' => $this->selectedDate,
            'personality_score' => $this->personalityScore,
            'performance_score' => $this->performanceScore,
            'hospitality_score' => $this->hospitalityScore,
            'customer_follow_up_score' => $this->customerFollowUpScore,
            'number_of_people' => $this->numberOfPeople,
            'supervised_level_score' => $this->supervisedLevelScore,
            'notes' => $this->kpiNotes,
            'photo_path' => $photoPath,
            'personality_kpis' => $this->personalityKpis,
            'team_management_kpis' => $this->teamManagementKpis,
            'customer_follow_up_kpis' => $this->customerFollowUpKpis,
            'supervised_level_kpis' => $this->supervisedLevelKpis,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ];
        
        // For updates, only include photo_path if a new photo was uploaded
        if ($this->photo === null && isset($this->measurement)) {
            unset($data['photo_path']);
        }
        
        // For this implementation, allow multiple entries per day
        // Remove the check for existing measurements to allow multiple entries per day
        $measurement = KpiMeasurement::create($data);
        $message = 'KPI data saved successfully!';
        
        // Log the action with the measurement ID
        KpiLog::create([
            'kpi_measurement_id' => $measurement->id,
            'user_id' => Auth::id(),
            'ranking_code_id' => $this->selectedRankingCode,
            'action' => 'kpi_created',
            'description' => 'KPI data submitted for ' . RankingCode::find($this->selectedRankingCode)->ranking_id,
            'logged_at' => now(),
        ]);
        
        session()->flash('message', $message);
        return redirect()->route('performance.kpi');
    }

    public function resetForm()
    {
        $this->personalityScore = '0/4';
        $this->performanceScore = '0/3';
        $this->hospitalityScore = '0/2';
        $this->customerFollowUpScore = 10; // Default to 10
        $this->supervisedLevelScore = 5; // Default to 5
        $this->numberOfPeople = 0;
        $this->kpiNotes = '';
        $this->photo = null;

        // Reset all checkboxes - ensure arrays exist first
        if (is_array($this->personalityKpis)) {
            foreach ($this->personalityKpis as $key => $kpi) {
                $this->personalityKpis[$key]['checked'] = false;
            }
        }
        
        if (is_array($this->teamManagementKpis)) {
            foreach ($this->teamManagementKpis as $key => $kpi) {
                $this->teamManagementKpis[$key]['checked'] = false;
            }
        }
        
        if (is_array($this->customerFollowUpKpis)) {
            foreach ($this->customerFollowUpKpis as $key => $kpi) {
                $this->customerFollowUpKpis[$key]['checked'] = false;
            }
        }
        
        if (is_array($this->supervisedLevelKpis)) {
            foreach ($this->supervisedLevelKpis as $key => $kpi) {
                $this->supervisedLevelKpis[$key]['checked'] = false;
            }
        }

        $this->updateScores();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        session(['kpi_active_tab' => $tab]);
    }

    public function render()
    {
        return view('livewire.kpi-tracking')->layout('layouts.app');
    }
}