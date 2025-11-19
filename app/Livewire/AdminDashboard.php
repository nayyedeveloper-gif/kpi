<?php

namespace App\Livewire;

use App\Models\KpiMeasurement;
use App\Models\KpiLog;
use App\Models\SalesTransaction;
use App\Models\RankingCode;
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
            'total_ranking_codes' => RankingCode::count(),
            'total_kpi_measurements' => KpiMeasurement::count(),
            'total_sales_transactions' => SalesTransaction::count(),
            'active_today' => KpiMeasurement::whereDate('measurement_date', Carbon::today())->count(),
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
            'avg_transaction' => $transactions->count() > 0 ? $transactions->avg('total_amount') : 0,
        ];
    }

    public function getTopPerformersProperty()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);
        
        return RankingCode::whereHas('kpiMeasurements', function ($query) use ($dateFrom) {
            $query->whereBetween('measurement_date', [$dateFrom, Carbon::now()]);
        })
        ->with(['kpiMeasurements' => function ($query) use ($dateFrom) {
            $query->whereBetween('measurement_date', [$dateFrom, Carbon::now()]);
        }])
        ->get()
        ->map(function ($rankingCode) {
            $measurements = $rankingCode->kpiMeasurements;
            $rankingCode->avg_score = $measurements->count() > 0 ? round($measurements->avg('total_score'), 2) : 0;
            $rankingCode->avg_percentage = $measurements->count() > 0 ? round($measurements->avg('percentage'), 1) : 0;
            $rankingCode->measurement_count = $measurements->count();
            $rankingCode->latest_score = $measurements->sortByDesc('measurement_date')->first()?->total_score ?? 0;
            
            return $rankingCode;
        })
        ->sortByDesc('avg_percentage')
        ->take(5);
    }

    public function getTopSalesPersonsProperty()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);
        
        return SalesTransaction::with('salesPerson')
            ->whereBetween('sale_date', [$dateFrom, Carbon::now()])
            ->selectRaw('sales_person_id, SUM(total_amount) as total_revenue, COUNT(*) as transaction_count, AVG(total_amount) as avg_transaction')
            ->groupBy('sales_person_id')
            ->orderBy('total_revenue', 'desc')
            ->take(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'sales_person' => $transaction->salesPerson,
                    'total_revenue' => $transaction->total_revenue,
                    'transaction_count' => $transaction->transaction_count,
                    'avg_transaction' => $transaction->avg_transaction,
                ];
            });
    }

    public function getRecentActivitiesProperty()
    {
        $activities = collect();
        
        // Recent KPI Measurements
        $recentKpi = KpiMeasurement::with('rankingCode')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($kpi) {
                $name = $kpi->rankingCode ? $kpi->rankingCode->name : 'Unknown User';
                return [
                    'type' => 'kpi',
                    'icon' => '📊',
                    'color' => 'blue',
                    'message' => "{$name} submitted KPI measurement",
                    'time' => $kpi->created_at,
                    'details' => "Score: {$kpi->total_score}/6 ({$kpi->percentage}%)",
                ];
            });
        
        // Recent Sales
        $recentSales = SalesTransaction::with('salesPerson')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($sale) {
                $salesPersonName = $sale->salesPerson ? $sale->salesPerson->name : 'Unknown';
                return [
                    'type' => 'sale',
                    'icon' => '💰',
                    'color' => 'emerald',
                    'message' => "{$salesPersonName} made a sale",
                    'time' => $sale->created_at,
                    'details' => number_format($sale->total_amount, 2) . " MMK ({$sale->quantity} items)",
                ];
            });
        
        return $activities
            ->merge($recentKpi)
            ->merge($recentSales)
            ->sortByDesc('time')
            ->take(10)
            ->values();
    }

    // public function getDepartmentPerformanceProperty()
    // {
    //     $dateFrom = Carbon::now()->subDays($this->dateRange);
        
    //     return Department::active()
    //         ->withCount(['users' => function ($query) {
    //             $query->where('is_active', true);
    //         }])
    //         ->get()
    //         ->map(function ($dept) use ($dateFrom) {
    //             $measurements = KpiMeasurement::whereHas('user', function ($query) use ($dept) {
    //                 $query->where('department_id', $dept->id);
    //             })
    //             ->whereBetween('measurement_date', [$dateFrom, Carbon::now()])
    //             ->get();
                
    //             $dept->avg_score = $measurements->count() > 0 ? round($measurements->avg('total_score'), 2) : 0;
    //             $dept->avg_percentage = $measurements->count() > 0 ? round($measurements->avg('percentage'), 1) : 0;
    //             $dept->measurement_count = $measurements->count();
                
    //             return $dept;
    //         })
    //         ->sortByDesc('avg_percentage')
    //         ->take(5);
    // }

    public function render()
    {
        return view('livewire.admin-dashboard', [
            'overviewStats' => $this->overviewStats,
            'kpiStats' => $this->kpiStats,
            'salesStats' => $this->salesStats,
            // 'bonusStats' => $this->bonusStats,
            'topPerformers' => $this->topPerformers,
            'topSalesPersons' => $this->topSalesPersons,
            'recentActivities' => $this->recentActivities,
            // 'departmentPerformance' => $this->departmentPerformance,
        ])->layout('layouts.app');
    }
}