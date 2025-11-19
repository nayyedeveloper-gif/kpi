<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\KpiMeasurement;
use Livewire\Component;

class UserProfile extends Component
{
    public $user;
    public $userId;
    public $activeTab = 'overview';

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->user = User::with(['rankingCode', 'department', 'role'])->findOrFail($userId);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function getKpiMeasurementsProperty()
    {
        return KpiMeasurement::with(['rankingCode', 'logs'])
            ->where('created_by', $this->userId)
            ->orderBy('measurement_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getPerformanceStatsProperty()
    {
        $measurements = $this->user->kpiMeasurements()
            ->selectRaw('COUNT(*) as total_measurements,
                        AVG(total_score) as avg_score,
                        MAX(total_score) as highest_score,
                        MIN(total_score) as lowest_score,
                        COUNT(CASE WHEN total_score >= 4 THEN 1 END) as good_measurements')
            ->first();

        return $measurements;
    }

    public function getMonthlyPerformanceProperty()
    {
        return $this->user->kpiMeasurements()
            ->selectRaw('DATE_FORMAT(measurement_date, "%Y-%m") as month,
                        COUNT(*) as measurement_count,
                        AVG(total_score) as avg_score')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
    }

    public function getRecentActivitiesProperty()
    {
        return $this->user->kpiLogs()
            ->with(['kpiMeasurement'])
            ->orderBy('logged_at', 'desc')
            ->limit(20)
            ->get();
    }

    public function render()
    {
        return view('livewire.user-profile', [
            'kpiMeasurements' => $this->kpiMeasurements,
            'performanceStats' => $this->performanceStats,
            'monthlyPerformance' => $this->monthlyPerformance,
            'recentActivities' => $this->recentActivities,
        ])->layout('layouts.app');
    }
}
