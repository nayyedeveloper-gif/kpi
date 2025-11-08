<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BonusAward;
use App\Services\BonusCalculationService;
use Carbon\Carbon;

class BonusAwardManagement extends Component
{
    use WithPagination;

    public $periodStart;
    public $periodEnd;
    public $filterStatus = 'pending';
    public $filterPerson = '';
    
    protected $bonusService;

    public function boot(BonusCalculationService $bonusService)
    {
        $this->bonusService = $bonusService;
    }

    public function mount()
    {
        $this->periodStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->periodEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function calculateBonuses()
    {
        // Check if there are any sales transactions in this period
        $transactionCount = \App\Models\SalesTransaction::whereBetween('sale_date', [$this->periodStart, $this->periodEnd])->count();
        
        if ($transactionCount === 0) {
            session()->flash('error', 'No sales transactions found for this period. Please add sales first.');
            return;
        }

        // Check if bonuses already calculated for this period
        $existingBonuses = BonusAward::whereBetween('period_start', [$this->periodStart, $this->periodEnd])
            ->where('status', '!=', 'rejected')
            ->count();

        if ($existingBonuses > 0) {
            session()->flash('error', "Bonuses already calculated for this period ({$existingBonuses} awards exist). Delete existing awards first if you want to recalculate.");
            return;
        }

        // Check if there are active bonus tiers
        $activeTiers = \App\Models\BonusTier::active()->count();
        
        if ($activeTiers === 0) {
            session()->flash('error', 'No active bonus tiers configured. Please set up bonus tiers first.');
            return;
        }

        $result = $this->bonusService->calculateBonusesUsingTiers(
            $this->periodStart,
            $this->periodEnd
        );

        if ($result['total_awards'] === 0) {
            session()->flash('message', 'No bonuses qualified. Sales may not meet minimum thresholds.');
        } else {
            session()->flash('message', "Successfully calculated {$result['total_awards']} bonuses totaling " . number_format($result['total_amount'], 2) . " MMK");
        }
    }

    public function approve($id)
    {
        $award = BonusAward::find($id);
        $award->update(['status' => 'approved']);
        session()->flash('message', 'Bonus approved successfully!');
    }

    public function reject($id)
    {
        $award = BonusAward::find($id);
        $award->delete();
        session()->flash('message', 'Bonus rejected and deleted!');
    }

    public function markAsPaid($id)
    {
        $award = BonusAward::find($id);
        $award->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
        session()->flash('message', 'Bonus marked as paid!');
    }

    public function render()
    {
        $awards = BonusAward::query()
            ->with(['salesPerson', 'bonusConfiguration'])
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterPerson, function ($query) {
                $query->where('sales_person_id', $this->filterPerson);
            })
            ->whereBetween('period_start', [$this->periodStart, $this->periodEnd])
            ->latest('awarded_at')
            ->paginate(20);

        $stats = [
            'pending_count' => BonusAward::pending()->whereBetween('period_start', [$this->periodStart, $this->periodEnd])->count(),
            'pending_amount' => BonusAward::pending()->whereBetween('period_start', [$this->periodStart, $this->periodEnd])->sum('bonus_amount'),
            'approved_count' => BonusAward::approved()->whereBetween('period_start', [$this->periodStart, $this->periodEnd])->count(),
            'approved_amount' => BonusAward::approved()->whereBetween('period_start', [$this->periodStart, $this->periodEnd])->sum('bonus_amount'),
            'paid_count' => BonusAward::paid()->whereBetween('period_start', [$this->periodStart, $this->periodEnd])->count(),
            'paid_amount' => BonusAward::paid()->whereBetween('period_start', [$this->periodStart, $this->periodEnd])->sum('bonus_amount'),
        ];

        return view('livewire.bonus-award-management', [
            'awards' => $awards,
            'stats' => $stats,
        ])->layout('layouts.app');
    }
}
