<?php

namespace App\Livewire;

use App\Models\KpiMeasurement;
use App\Models\KpiLog;
use App\Models\User;
use App\Models\Department;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KpiAnalytics extends Component
{
    public $dateFrom;
    public $dateTo;
    public $selectedUsers = [];
    public $selectedDepartment = '';
    public $viewType = 'overview'; // overview, comparison, individual
    
    public $users;
    public $departments;

    public function mount()
    {
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
        $this->users = User::active()->with(['department', 'position'])->get();
        $this->departments = Department::active()->get();
    }

    public function getOverviewDataProperty()
    {
        $query = KpiMeasurement::with('user')
            ->whereBetween('measurement_date', [$this->dateFrom, $this->dateTo]);

        if ($this->selectedDepartment) {
            $query->whereHas('user', function ($q) {
                $q->where('department_id', $this->selectedDepartment);
            });
        }

        $measurements = $query->get();
        $total = $measurements->count();

        return [
            'total_measurements' => $total,
            'avg_score' => $total > 0 ? round($measurements->avg(function ($m) {
                return $m->total_score ?? 0;
            }), 2) : 0,
            'avg_percentage' => $total > 0 ? round($measurements->avg(function ($m) {
                return $m->percentage ?? 0;
            }), 2) : 0,
            'good_logs' => KpiLog::good()
                ->whereBetween('logged_at', [$this->dateFrom, $this->dateTo])
                ->when($this->selectedDepartment, function ($q) {
                    $q->whereHas('user', function ($query) {
                        $query->where('department_id', $this->selectedDepartment);
                    });
                })
                ->count(),
            'bad_logs' => KpiLog::bad()
                ->whereBetween('logged_at', [$this->dateFrom, $this->dateTo])
                ->when($this->selectedDepartment, function ($q) {
                    $q->whereHas('user', function ($query) {
                        $query->where('department_id', $this->selectedDepartment);
                    });
                })
                ->count(),
        ];
    }

    public function getDailyTrendProperty()
    {
        $measurements = KpiMeasurement::whereBetween('measurement_date', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedDepartment, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('department_id', $this->selectedDepartment);
                });
            })
            ->get()
            ->groupBy(function ($item) {
                return $item->measurement_date->format('Y-m-d');
            })
            ->map(function ($group) {
                return [
                    'date' => $group->first()->measurement_date->format('M d'),
                    'avg_score' => round($group->avg('total_score'), 2),
                    'count' => $group->count(),
                ];
            })
            ->values();

        return $measurements;
    }

    public function getKpiBreakdownProperty()
    {
        $measurements = KpiMeasurement::whereBetween('measurement_date', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedDepartment, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('department_id', $this->selectedDepartment);
                });
            })
            ->get();

        $total = $measurements->count();
        
        if ($total === 0) {
            return [
                'ready_to_sale' => ['name' => 'Ready to Sale', 'percentage' => 0, 'count' => 0],
                'counter_check' => ['name' => 'Counter Check', 'percentage' => 0, 'count' => 0],
                'cleanliness' => ['name' => 'Cleanliness', 'percentage' => 0, 'count' => 0],
                'stock_check' => ['name' => 'Stock Check', 'percentage' => 0, 'count' => 0],
                'order_handling' => ['name' => 'Order Handling', 'percentage' => 0, 'count' => 0],
                'customer_followup' => ['name' => 'Customer Followup', 'percentage' => 0, 'count' => 0],
            ];
        }

        return [
            'ready_to_sale' => [
                'name' => 'Ready to Sale',
                'count' => $measurements->filter(fn($m) => $m->ready_to_sale == 1 || $m->ready_to_sale === true)->count(),
                'percentage' => round(($measurements->filter(fn($m) => $m->ready_to_sale == 1 || $m->ready_to_sale === true)->count() / $total) * 100, 1),
            ],
            'counter_check' => [
                'name' => 'Counter Check',
                'count' => $measurements->filter(fn($m) => $m->counter_check == 1 || $m->counter_check === true)->count(),
                'percentage' => round(($measurements->filter(fn($m) => $m->counter_check == 1 || $m->counter_check === true)->count() / $total) * 100, 1),
            ],
            'cleanliness' => [
                'name' => 'Cleanliness',
                'count' => $measurements->filter(fn($m) => $m->cleanliness == 1 || $m->cleanliness === true)->count(),
                'percentage' => round(($measurements->filter(fn($m) => $m->cleanliness == 1 || $m->cleanliness === true)->count() / $total) * 100, 1),
            ],
            'stock_check' => [
                'name' => 'Stock Check',
                'count' => $measurements->filter(fn($m) => $m->stock_check == 1 || $m->stock_check === true)->count(),
                'percentage' => round(($measurements->filter(fn($m) => $m->stock_check == 1 || $m->stock_check === true)->count() / $total) * 100, 1),
            ],
            'order_handling' => [
                'name' => 'Order Handling',
                'count' => $measurements->filter(fn($m) => $m->order_handling == 1 || $m->order_handling === true)->count(),
                'percentage' => round(($measurements->filter(fn($m) => $m->order_handling == 1 || $m->order_handling === true)->count() / $total) * 100, 1),
            ],
            'customer_followup' => [
                'name' => 'Customer Followup',
                'count' => $measurements->filter(fn($m) => $m->customer_followup == 1 || $m->customer_followup === true)->count(),
                'percentage' => round(($measurements->filter(fn($m) => $m->customer_followup == 1 || $m->customer_followup === true)->count() / $total) * 100, 1),
            ],
        ];
    }

    public function getTopPerformersProperty()
    {
        $users = User::active()
            ->with(['department', 'position', 'kpiMeasurements' => function ($query) {
                $query->whereBetween('measurement_date', [$this->dateFrom, $this->dateTo]);
            }])
            ->when($this->selectedDepartment, function ($query) {
                $query->where('department_id', $this->selectedDepartment);
            })
            ->get()
            ->filter(function ($user) {
                return $user->kpiMeasurements->count() > 0;
            })
            ->map(function ($user) {
                $measurements = $user->kpiMeasurements;
                $user->kpi_measurements_count = $measurements->count();
                $user->avg_score = $measurements->count() > 0 
                    ? round($measurements->avg('total_score'), 2) 
                    : 0;
                return $user;
            })
            ->sortByDesc('avg_score')
            ->take(10)
            ->values();

        return $users;
    }

    public function getUserComparisonProperty()
    {
        if (empty($this->selectedUsers)) {
            return collect();
        }

        return User::whereIn('id', $this->selectedUsers)
            ->with(['department', 'position'])
            ->get()
            ->map(function ($user) {
                $measurements = $user->kpiMeasurements()
                    ->whereBetween('measurement_date', [$this->dateFrom, $this->dateTo])
                    ->get();
                
                $total = $measurements->count();

                return [
                    'user' => $user,
                    'total_measurements' => $total,
                    'avg_score' => $total > 0 ? round($measurements->avg('total_score') ?? 0, 2) : 0,
                    'avg_percentage' => $total > 0 ? round($measurements->avg(function ($m) {
                        return $m->percentage ?? 0;
                    }), 2) : 0,
                    'good_logs' => $user->kpiLogs()->good()
                        ->whereBetween('logged_at', [$this->dateFrom, $this->dateTo])
                        ->count(),
                    'bad_logs' => $user->kpiLogs()->bad()
                        ->whereBetween('logged_at', [$this->dateFrom, $this->dateTo])
                        ->count(),
                ];
            });
    }

    public function render()
    {
        return view('livewire.kpi-analytics', [
            'overviewData' => $this->overviewData,
            'dailyTrend' => $this->dailyTrend,
            'kpiBreakdown' => $this->kpiBreakdown,
            'topPerformers' => $this->topPerformers,
            'userComparison' => $this->userComparison,
        ])->layout('layouts.app');
    }
}
