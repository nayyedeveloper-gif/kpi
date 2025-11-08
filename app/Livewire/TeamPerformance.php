<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Department;
use Livewire\Component;
use Carbon\Carbon;

class TeamPerformance extends Component
{
    public $selectedDepartment = '';
    public $sortBy = 'performance'; // performance, name, position
    public $dateRange = 30; // Last 30 days
    public $selectedUsers = [];
    public $showCompareModal = false;

    public function mount()
    {
        //
    }

    public function getTeamMembersProperty()
    {
        // Exclude top management positions
        $excludedPositions = ['CEO', 'Director', 'General Manager', 'Deputy General Manager'];

        $dateFrom = Carbon::now()->subDays($this->dateRange);
        $dateTo = Carbon::now();

        $users = User::active()
            ->with(['department', 'position', 'kpiMeasurements' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('measurement_date', [$dateFrom, $dateTo]);
            }, 'kpiLogs' => function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('logged_at', [$dateFrom, $dateTo]);
            }])
            ->whereHas('position', function ($query) use ($excludedPositions) {
                $query->whereNotIn('name', $excludedPositions);
            })
            ->when($this->selectedDepartment, function ($query) {
                $query->where('department_id', $this->selectedDepartment);
            })
            ->get()
            ->map(function ($user) {
                $measurements = $user->kpiMeasurements;
                $goodLogs = $user->kpiLogs->where('status', 'good')->count();
                $badLogs = $user->kpiLogs->where('status', 'bad')->count();
                
                $avgScore = $measurements->count() > 0 ? round($measurements->avg('total_score'), 2) : 0;
                $avgPercentage = $measurements->count() > 0 ? round($measurements->avg(function ($m) {
                    return $m->percentage;
                }), 2) : 0;

                // Determine performance level
                $performanceLevel = 'red'; // Poor
                if ($avgPercentage >= 76) {
                    $performanceLevel = 'green'; // Excellent
                } elseif ($avgPercentage >= 51) {
                    $performanceLevel = 'yellow'; // Average
                }

                return [
                    'user' => $user,
                    'avg_score' => $avgScore,
                    'avg_percentage' => $avgPercentage,
                    'good_logs' => $goodLogs,
                    'bad_logs' => $badLogs,
                    'total_measurements' => $measurements->count(),
                    'performance_level' => $performanceLevel,
                ];
            });

        // Sort
        if ($this->sortBy === 'performance') {
            $users = $users->sortByDesc('avg_percentage');
        } elseif ($this->sortBy === 'name') {
            $users = $users->sortBy('user.name');
        } elseif ($this->sortBy === 'position') {
            $users = $users->sortBy('user.position.hierarchy_level');
        }

        return $users->values();
    }

    public function getDepartmentsProperty()
    {
        return Department::active()->orderBy('name')->get();
    }

    public function toggleUserSelection($userId)
    {
        if (in_array($userId, $this->selectedUsers)) {
            $this->selectedUsers = array_diff($this->selectedUsers, [$userId]);
        } else {
            if (count($this->selectedUsers) < 5) { // Max 5 users to compare
                $this->selectedUsers[] = $userId;
            }
        }
    }

    public function compareSelected()
    {
        if (count($this->selectedUsers) >= 2) {
            $this->showCompareModal = true;
        }
    }

    public function closeCompareModal()
    {
        $this->showCompareModal = false;
    }

    public function clearSelection()
    {
        $this->selectedUsers = [];
    }

    public function render()
    {
        return view('livewire.team-performance', [
            'teamMembers' => $this->teamMembers,
            'departments' => $this->departments,
        ])->layout('layouts.app');
    }
}
