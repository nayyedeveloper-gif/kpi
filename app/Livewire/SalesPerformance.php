<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\BonusCalculationService;
use App\Models\SalesTransaction;
use App\Models\BonusAward;
use App\Models\User;
use Carbon\Carbon;

class SalesPerformance extends Component
{
    public $periodStart;
    public $periodEnd;
    public $viewType = 'leaderboard'; // leaderboard, individual, transactions
    public $selectedSalesPerson;
    public $rankingType = 'revenue'; // revenue, quantity, mixed

    protected $bonusService;

    public function boot(BonusCalculationService $bonusService)
    {
        $this->bonusService = $bonusService;
    }

    public function mount()
    {
        // Default to current month
        $this->periodStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->periodEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function switchView($type)
    {
        $this->viewType = $type;
    }

    public function selectPeriod($period)
    {
        switch ($period) {
            case 'this_month':
                $this->periodStart = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->periodEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $this->periodStart = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->periodEnd = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'this_quarter':
                $this->periodStart = Carbon::now()->startOfQuarter()->format('Y-m-d');
                $this->periodEnd = Carbon::now()->endOfQuarter()->format('Y-m-d');
                break;
            case 'this_year':
                $this->periodStart = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->periodEnd = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
        }
    }

    public function changeRankingType($type)
    {
        $this->rankingType = $type;
    }

    public function viewIndividual($salesPersonId)
    {
        return redirect()->route('sales.individual', ['id' => $salesPersonId, 'start' => $this->periodStart, 'end' => $this->periodEnd]);
    }

    public function getLeaderboardProperty()
    {
        return $this->bonusService->getLeaderboard(
            $this->periodStart,
            $this->periodEnd,
            $this->rankingType
        );
    }

    public function getIndividualSummaryProperty()
    {
        if (!$this->selectedSalesPerson) {
            return null;
        }

        return $this->bonusService->getIndividualSummary(
            $this->selectedSalesPerson,
            $this->periodStart,
            $this->periodEnd
        );
    }

    public function getTopPerformersProperty()
    {
        return $this->leaderboard->take(3);
    }

    public function getRecentTransactionsProperty()
    {
        return SalesTransaction::with(['salesPerson'])
            ->whereBetween('sale_date', [$this->periodStart, $this->periodEnd])
            ->latest('sale_date')
            ->take(10)
            ->get();
    }

    public function getPendingBonusesProperty()
    {
        return BonusAward::with(['salesPerson', 'bonusConfiguration'])
            ->pending()
            ->forPeriod($this->periodStart, $this->periodEnd)
            ->latest()
            ->get();
    }

    public function getTotalStatsProperty()
    {
        $transactions = SalesTransaction::whereBetween('sale_date', [$this->periodStart, $this->periodEnd])->get();
        
        return [
            'total_revenue' => $transactions->sum('total_amount'),
            'total_quantity' => $transactions->sum('quantity'),
            'total_transactions' => $transactions->count(),
            'total_commission' => $transactions->sum('commission_amount'),
            'avg_transaction' => $transactions->avg('total_amount') ?? 0,
            'total_sales_people' => $transactions->pluck('sales_person_id')->unique()->count(),
        ];
    }

    public function getSalesPersonsProperty()
    {
        return User::active()->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.sales-performance', [
            'leaderboard' => $this->leaderboard,
            'topPerformers' => $this->topPerformers,
            'recentTransactions' => $this->recentTransactions,
            'pendingBonuses' => $this->pendingBonuses,
            'totalStats' => $this->totalStats,
            'individualSummary' => $this->individualSummary,
            'salesPersons' => $this->salesPersons,
        ])->layout('layouts.app');
    }
}
