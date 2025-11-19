<?php

namespace App\Livewire;

use App\Models\EntryLevelChecklist;
use App\Models\EntryLevelImpact;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EntryLevelTracking extends Component
{
    use WithFileUploads;

    public $selectedDate;
    public $selectedUser;
    public $checklist;
    public $users;
    public $showLogModal = false;
    public $showDetailsModal = false;
    public $selectedArea;
    
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
        $this->loadExistingChecklist();
    }

    public function updatedSelectedUser()
    {
        $this->loadExistingChecklist();
    }

    public function updatedSelectedDate()
    {
        $this->loadExistingChecklist();
    }

    public function loadExistingChecklist()
    {
        if ($this->selectedUser && $this->selectedDate) {
            $this->checklist = EntryLevelChecklist::where('user_id', $this->selectedUser)
                ->whereDate('evaluation_date', $this->selectedDate)
                ->first();

            if (!$this->checklist) {
                $this->checklist = EntryLevelChecklist::create([
                    'user_id' => $this->selectedUser,
                    'evaluator_id' => Auth::id(),
                    'evaluation_date' => $this->selectedDate,
                    'personality_score' => false,
                    'performance_score' => false,
                    'hospitality_score' => false,
                    'cleaning_score' => false,
                    'learning_achievement_score' => false,
                    'total_score' => 0,
                    'status' => 'violation',
                ]);
            }
        }
    }

    public function openLogModal($areaField)
    {
        $this->selectedArea = $areaField;
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

        if (!$this->checklist) {
            $this->loadExistingChecklist();
        }

        // Handle photo upload
        $photoPath = null;
        if ($this->logPhoto) {
            $photoPath = $this->logPhoto->store('entry-level-photos', 'public');
        }

        // Update checklist field
        $updateData = [
            $this->selectedArea => ($this->logStatus === 'good'),
            $this->selectedArea . '_notes' => $this->logNotes,
        ];

        // Store photo path if uploaded (you might want to add a photo column to the table)
        // For now, we'll append it to notes if photo exists
        if ($photoPath) {
            $updateData[$this->selectedArea . '_notes'] = $this->logNotes . ' [Photo: ' . $photoPath . ']';
        }

        $this->checklist->update($updateData);

        // Recalculate total score and status
        $this->checklist->total_score = $this->checklist->calculateTotalScore();
        $this->checklist->status = $this->checklist->determineStatus();
        $this->checklist->save();

        // Create impact records if violation
        if ($this->checklist->status === 'violation') {
            $this->createImpactRecords($this->checklist);
        }

        session()->flash('message', 'Entry level evaluation updated successfully!');
        $this->showLogModal = false;
        $this->loadExistingChecklist();
    }

    private function createImpactRecords($checklist)
    {
        // Delete existing impacts for this checklist
        EntryLevelImpact::where('checklist_id', $checklist->id)->delete();

        $user = User::find($checklist->user_id);
        
        // Impact on Supervisor
        if ($user->supervisor_id) {
            EntryLevelImpact::create([
                'checklist_id' => $checklist->id,
                'affected_user_id' => $user->supervisor_id,
                'affected_role' => 'supervisor',
                'impact_score' => -5,
                'impact_notes' => 'Entry level violation by ' . $user->name,
            ]);
        }
        
        // Impact on Manager (department head)
        if ($user->department && $user->department->manager_id) {
            EntryLevelImpact::create([
                'checklist_id' => $checklist->id,
                'affected_user_id' => $user->department->manager_id,
                'affected_role' => 'manager',
                'impact_score' => -3,
                'impact_notes' => 'Entry level violation by team member ' . $user->name,
            ]);
        }
    }

    public function viewDetails($date = null)
    {
        $this->viewDate = $date ?? $this->selectedDate;
        
        if ($this->checklist) {
            // Get all logs for this checklist (simplified - you can enhance this)
            $this->dailyLogs = collect([
                (object)[
                    'status' => $this->checklist->personality_score ? 'good' : 'bad',
                    'area_name' => 'Personality',
                    'notes' => $this->checklist->personality_notes,
                    'logged_at' => $this->checklist->updated_at,
                    'user' => $this->checklist->evaluator,
                ],
                (object)[
                    'status' => $this->checklist->performance_score ? 'good' : 'bad',
                    'area_name' => 'Performance',
                    'notes' => $this->checklist->performance_notes,
                    'logged_at' => $this->checklist->updated_at,
                    'user' => $this->checklist->evaluator,
                ],
                (object)[
                    'status' => $this->checklist->hospitality_score ? 'good' : 'bad',
                    'area_name' => 'Hospitality',
                    'notes' => $this->checklist->hospitality_notes,
                    'logged_at' => $this->checklist->updated_at,
                    'user' => $this->checklist->evaluator,
                ],
                (object)[
                    'status' => $this->checklist->cleaning_score ? 'good' : 'bad',
                    'area_name' => 'Cleaning',
                    'notes' => $this->checklist->cleaning_notes,
                    'logged_at' => $this->checklist->updated_at,
                    'user' => $this->checklist->evaluator,
                ],
                (object)[
                    'status' => $this->checklist->learning_achievement_score ? 'good' : 'bad',
                    'area_name' => 'Learning Achievement',
                    'notes' => $this->checklist->learning_achievement_notes,
                    'logged_at' => $this->checklist->updated_at,
                    'user' => $this->checklist->evaluator,
                ],
            ]);
        }
        
        $this->showDetailsModal = true;
    }

    public function closeModals()
    {
        $this->showLogModal = false;
        $this->showDetailsModal = false;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.entry-level-tracking');
    }
}
