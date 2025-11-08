<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\KpiMeasurement;
use App\Models\KpiLog;
use App\Models\Department;
use App\Models\Position;
use App\Models\SalesTransaction;
use App\Models\BonusAward;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboard extends Component
{
    public $dateRange = 30; // Last 30 days

    public function mount()
    {
        //
    }

    public function getOverviewStatsProperty()
    {
        return [
            'total_users' => User::active()->count(),
            'total_departments' => Department::where('is_active', true)->count(),
            'total_positions' => Position::where('is_active', true)->count(),
            'total_branches' => \App\Models\Branch::where('is_active', true)->count(),
        ];
    }

    public function getKpiStatsProperty()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);
        
        return [
            'today_measurements' => KpiMeasurement::whereDate('measurement_date', Carbon::today())->count(),
            'this_month_measurements' => KpiMeasurement::whereMonth('measurement_date', Carbon::now()->month)->count(),
            'total_measurements' => KpiMeasurement::whereBetween('measurement_date', [$dateFrom, Carbon::now()])->count(),
            'avg_score' => round(KpiMeasurement::whereBetween('measurement_date', [$dateFrom, Carbon::now()])->avg('total_score') ?? 0, 2),
            'avg_percentage' => round(KpiMeasurement::whereBetween('measurement_date', [$dateFrom, Carbon::now()])->avg('percentage') ?? 0, 1),
            'good_logs' => KpiLog::good()->whereBetween('logged_at', [$dateFrom, Carbon::now()])->count(),
            'bad_logs' => KpiLog::bad()->whereBetween('logged_at', [$dateFrom, Carbon::now()])->count(),
        ];
    }

    public function getSalesStatsProperty()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);
        
        $transactions = SalesTransaction::whereBetween('sale_date', [$dateFrom, Carbon::now()]);
        
        return [
            'total_revenue' => $transactions->sum('total_amount'),
            'total_transactions' => $transactions->count(),
            'total_items_sold' => $transactions->sum('quantity'),
            'total_commission' => $transactions->sum('commission_amount'),
            'today_revenue' => SalesTransaction::whereDate('sale_date', Carbon::today())->sum('total_amount'),
            'today_transactions' => SalesTransaction::whereDate('sale_date', Carbon::today())->count(),
        ];
    }

    public function getBonusStatsProperty()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);
        
        return [
            'pending_bonuses' => BonusAward::pending()->whereBetween('period_start', [$dateFrom, Carbon::now()])->count(),
            'pending_amount' => BonusAward::pending()->whereBetween('period_start', [$dateFrom, Carbon::now()])->sum('bonus_amount'),
            'approved_bonuses' => BonusAward::approved()->whereBetween('period_start', [$dateFrom, Carbon::now()])->count(),
            'approved_amount' => BonusAward::approved()->whereBetween('period_start', [$dateFrom, Carbon::now()])->sum('bonus_amount'),
            'paid_bonuses' => BonusAward::paid()->whereBetween('period_start', [$dateFrom, Carbon::now()])->count(),
            'paid_amount' => BonusAward::paid()->whereBetween('period_start', [$dateFrom, Carbon::now()])->sum('bonus_amount'),
        ];
    }

    public function getTopPerformersProperty()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);
        
        return User::active()
            ->withCount(['kpiMeasurements' => function ($query) use ($dateFrom) {
                $query->whereBetween('measurement_date', [$dateFrom, Carbon::now()]);
            }])
            ->with(['department', 'position'])
            ->having('kpi_measurements_count', '>', 0)
            ->orderBy('kpi_measurements_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($user) use ($dateFrom) {
                $measurements = $user->kpiMeasurements()
                    ->whereBetween('measurement_date', [$dateFrom, Carbon::now()])
                    ->get();
                
                $user->avg_score = $measurements->count() > 0 ? round($measurements->avg('total_score'), 2) : 0;
                $user->avg_percentage = $measurements->count() > 0 ? round($measurements->avg('percentage'), 1) : 0;
                
                return $user;
            });
    }

    public function getTopSalesPersonsProperty()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);
        
        return User::active()
            ->join('sales_transactions', 'users.id', '=', 'sales_transactions.sales_person_id')
            ->whereBetween('sales_transactions.sale_date', [$dateFrom, Carbon::now()])
            ->groupBy('users.id', 'users.name', 'users.email', 'users.profile_photo', 'users.department_id', 'users.position_id')
            ->selectRaw('users.id, users.name, users.email, users.profile_photo, users.department_id, users.position_id, SUM(sales_transactions.total_amount) as total_revenue, COUNT(sales_transactions.id) as transaction_count')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();
    }

    public function getRecentActivitiesProperty()
    {
        $activities = collect();
        
        // Recent KPI Measurements
        $recentKpi = KpiMeasurement::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($kpi) {
                return [
                    'type' => 'kpi',
                    'icon' => '📊',
                    'color' => 'blue',
                    'message' => "{$kpi->user->name} submitted KPI measurement",
                    'time' => $kpi->created_at,
                    'details' => "Score: {$kpi->total_score}/6",
                ];
            });
        
        // Recent Sales
        $recentSales = SalesTransaction::with('salesPerson')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($sale) {
                return [
                    'type' => 'sale',
                    'icon' => '💰',
                    'color' => 'emerald',
                    'message' => "{$sale->salesPerson->name} made a sale",
                    'time' => $sale->created_at,
                    'details' => number_format($sale->total_amount, 2) . " MMK",
                ];
            });
        
        // Recent Bonus Awards
        $recentBonuses = BonusAward::with('salesPerson')
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function ($bonus) {
                return [
                    'type' => 'bonus',
                    'icon' => '🎁',
                    'color' => 'purple',
                    'message' => "{$bonus->salesPerson->name} received bonus",
                    'time' => $bonus->created_at,
                    'details' => number_format($bonus->bonus_amount, 2) . " MMK - " . ucfirst($bonus->status),
                ];
            });
        
        return $activities
            ->merge($recentKpi)
            ->merge($recentSales)
            ->merge($recentBonuses)
            ->sortByDesc('time')
            ->take(10)
            ->values();
    }

    public function getDepartmentPerformanceProperty()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);
        
        return Department::active()
            ->withCount(['users' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get()
            ->map(function ($dept) use ($dateFrom) {
                $measurements = KpiMeasurement::whereHas('user', function ($query) use ($dept) {
                    $query->where('department_id', $dept->id);
                })
                ->whereBetween('measurement_date', [$dateFrom, Carbon::now()])
                ->get();
                
                $dept->avg_score = $measurements->count() > 0 ? round($measurements->avg('total_score'), 2) : 0;
                $dept->avg_percentage = $measurements->count() > 0 ? round($measurements->avg('percentage'), 1) : 0;
                $dept->measurement_count = $measurements->count();
                
                return $dept;
            })
            ->sortByDesc('avg_percentage')
            ->take(5);
    }

    public function render()
    {
        return view('livewire.admin-dashboard', [
            'overviewStats' => $this->overviewStats,
            'kpiStats' => $this->kpiStats,
            'salesStats' => $this->salesStats,
            'bonusStats' => $this->bonusStats,
            'topPerformers' => $this->topPerformers,
            'topSalesPersons' => $this->topSalesPersons,
            'recentActivities' => $this->recentActivities,
            'departmentPerformance' => $this->departmentPerformance,
        ])->layout('layouts.app');
    }
}