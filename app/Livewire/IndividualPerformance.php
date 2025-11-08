<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\SalesTransaction;
use App\Models\BonusAward;
use App\Services\BonusCalculationService;
use Carbon\Carbon;

class IndividualPerformance extends Component
{
    public $salesPersonId;
    public $periodStart;
    public $periodEnd;
    
    protected $bonusService;

    public function boot(BonusCalculationService $bonusService)
    {
        $this->bonusService = $bonusService;
    }

    public function mount($id, $start = null, $end = null)
    {
        $this->salesPersonId = $id;
        $this->periodStart = $start ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->periodEnd = $end ?? Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $salesPerson = User::findOrFail($this->salesPersonId);
        
        $summary = $this->bonusService->getIndividualSummary(
            $this->salesPersonId,
            $this->periodStart,
            $this->periodEnd
        );

        $transactions = SalesTransaction::where('sales_person_id', $this->salesPersonId)
            ->whereBetween('sale_date', [$this->periodStart, $this->periodEnd])
            ->latest('sale_date')
            ->get();

        $bonusAwards = BonusAward::where('sales_person_id', $this->salesPersonId)
            ->whereBetween('period_start', [$this->periodStart, $this->periodEnd])
            ->with('bonusConfiguration')
            ->latest('awarded_at')
            ->get();

        // Daily sales chart data
        $dailySales = SalesTransaction::where('sales_person_id', $this->salesPersonId)
            ->whereBetween('sale_date', [$this->periodStart, $this->periodEnd])
            ->selectRaw('DATE(sale_date) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('livewire.individual-performance', [
            'salesPerson' => $salesPerson,
            'summary' => $summary,
            'transactions' => $transactions,
            'bonusAwards' => $bonusAwards,
            'dailySales' => $dailySales,
        ])->layout('layouts.app');
    }
}
