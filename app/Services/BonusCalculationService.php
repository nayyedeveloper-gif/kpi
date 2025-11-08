<?php

namespace App\Services;

use App\Models\SalesTransaction;
use App\Models\BonusConfiguration;
use App\Models\BonusAward;
use App\Models\BonusTier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BonusCalculationService
{
    /**
     * Calculate bonuses for a given period
     */
    public function calculateBonusesForPeriod($periodStart, $periodEnd, $bonusConfigId = null)
    {
        $configurations = $bonusConfigId 
            ? BonusConfiguration::where('id', $bonusConfigId)->active()->get()
            : BonusConfiguration::active()->get();

        $results = [];

        foreach ($configurations as $config) {
            $result = $this->calculateForConfiguration($config, $periodStart, $periodEnd);
            $results[] = $result;
        }

        return $results;
    }

    /**
     * Calculate bonus for specific configuration
     */
    private function calculateForConfiguration($config, $periodStart, $periodEnd)
    {
        // Get sales performance for all sales people
        $performances = $this->getSalesPerformance($periodStart, $periodEnd);

        // Filter by minimum requirements
        $qualified = $performances->filter(function ($perf) use ($config) {
            return $perf['total_revenue'] >= $config->minimum_revenue 
                && $perf['total_quantity'] >= $config->minimum_quantity;
        });

        // Rank based on type
        $ranked = $this->rankPerformances($qualified, $config->type);

        // Award bonuses to top performers
        $awards = [];
        $topPerformers = $ranked->take($config->rank_limit);

        foreach ($topPerformers as $index => $performer) {
            $rank = $index + 1;
            $bonusAmount = $this->calculateBonusAmount($config, $performer, $rank);

            if ($bonusAmount > 0) {
                $award = $this->createBonusAward(
                    $performer,
                    $config,
                    $periodStart,
                    $periodEnd,
                    $rank,
                    $bonusAmount
                );
                $awards[] = $award;
            }
        }

        return [
            'configuration' => $config->name,
            'qualified_count' => $qualified->count(),
            'awarded_count' => count($awards),
            'total_bonus' => collect($awards)->sum('bonus_amount'),
            'awards' => $awards,
        ];
    }

    /**
     * Get sales performance for all sales people
     */
    public function getSalesPerformance($periodStart, $periodEnd)
    {
        return SalesTransaction::select(
                'sales_person_id',
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('AVG(total_amount) as avg_transaction')
            )
            ->whereBetween('sale_date', [$periodStart, $periodEnd])
            ->groupBy('sales_person_id')
            ->with('salesPerson')
            ->get()
            ->map(function ($item) {
                return [
                    'sales_person_id' => $item->sales_person_id,
                    'sales_person' => $item->salesPerson,
                    'total_revenue' => (float) $item->total_revenue,
                    'total_quantity' => (int) $item->total_quantity,
                    'total_transactions' => (int) $item->total_transactions,
                    'avg_transaction' => (float) $item->avg_transaction,
                ];
            });
    }

    /**
     * Rank performances based on type
     */
    private function rankPerformances($performances, $type)
    {
        switch ($type) {
            case 'revenue':
                return $performances->sortByDesc('total_revenue')->values();
            case 'quantity':
                return $performances->sortByDesc('total_quantity')->values();
            case 'mixed':
                // Weighted score: 60% revenue, 40% quantity
                return $performances->map(function ($perf) {
                    $perf['score'] = ($perf['total_revenue'] * 0.6) + ($perf['total_quantity'] * 0.4);
                    return $perf;
                })->sortByDesc('score')->values();
            default:
                return $performances->sortByDesc('total_revenue')->values();
        }
    }

    /**
     * Calculate bonus amount based on configuration
     */
    private function calculateBonusAmount($config, $performer, $rank)
    {
        // Check if criteria has rank-specific amounts
        if ($config->criteria && isset($config->criteria['rank_bonuses'])) {
            return $config->criteria['rank_bonuses'][$rank] ?? 0;
        }

        // Use flat bonus amount
        if ($config->bonus_amount) {
            // Decrease bonus by rank (1st gets full, 2nd gets 75%, 3rd gets 50%)
            $multiplier = 1 - (($rank - 1) * 0.25);
            return $config->bonus_amount * max($multiplier, 0.25);
        }

        // Use percentage of revenue
        if ($config->bonus_percentage) {
            return ($performer['total_revenue'] * $config->bonus_percentage) / 100;
        }

        return 0;
    }

    /**
     * Create bonus award record
     */
    private function createBonusAward($performer, $config, $periodStart, $periodEnd, $rank, $bonusAmount)
    {
        return BonusAward::create([
            'sales_person_id' => $performer['sales_person_id'],
            'bonus_configuration_id' => $config->id,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'total_revenue' => $performer['total_revenue'],
            'total_quantity' => $performer['total_quantity'],
            'total_transactions' => $performer['total_transactions'],
            'rank' => $rank,
            'bonus_amount' => $bonusAmount,
            'bonus_type' => $config->type,
            'reason' => "Rank #{$rank} in {$config->name}",
            'status' => 'pending',
            'awarded_at' => now(),
        ]);
    }

    /**
     * Get leaderboard for period
     */
    public function getLeaderboard($periodStart, $periodEnd, $type = 'revenue')
    {
        $performances = $this->getSalesPerformance($periodStart, $periodEnd);
        return $this->rankPerformances($performances, $type);
    }

    /**
     * Get individual performance summary
     */
    public function getIndividualSummary($salesPersonId, $periodStart, $periodEnd)
    {
        $transactions = SalesTransaction::where('sales_person_id', $salesPersonId)
            ->whereBetween('sale_date', [$periodStart, $periodEnd])
            ->get();

        $awards = BonusAward::where('sales_person_id', $salesPersonId)
            ->forPeriod($periodStart, $periodEnd)
            ->with('bonusConfiguration')
            ->get();

        return [
            'total_revenue' => $transactions->sum('total_amount'),
            'total_quantity' => $transactions->sum('quantity'),
            'total_transactions' => $transactions->count(),
            'avg_transaction' => $transactions->avg('total_amount') ?? 0,
            'total_commission' => $transactions->sum('commission_amount'),
            'total_bonus' => $awards->sum('bonus_amount'),
            'bonus_count' => $awards->count(),
            'transactions' => $transactions,
            'awards' => $awards,
        ];
    }

    /**
     * Calculate bonuses using BonusTier configuration
     */
    public function calculateBonusesUsingTiers($periodStart, $periodEnd)
    {
        $performances = $this->getSalesPerformance($periodStart, $periodEnd);
        $results = [];

        foreach ($performances as $performance) {
            $bonuses = $this->calculateTierBonusesForPerson($performance, $periodStart, $periodEnd);
            
            if (!empty($bonuses)) {
                foreach ($bonuses as $bonus) {
                    $award = BonusAward::create([
                        'sales_person_id' => $performance['sales_person_id'],
                        'bonus_configuration_id' => null,
                        'period_start' => $periodStart,
                        'period_end' => $periodEnd,
                        'total_revenue' => $performance['total_revenue'],
                        'total_quantity' => $performance['total_quantity'],
                        'total_transactions' => $performance['total_transactions'],
                        'rank' => null,
                        'bonus_amount' => $bonus['amount'],
                        'bonus_type' => $bonus['type'],
                        'reason' => $bonus['reason'],
                        'status' => 'pending',
                        'awarded_at' => now(),
                    ]);
                    
                    $results[] = $award;
                }
            }
        }

        return [
            'total_awards' => count($results),
            'total_amount' => collect($results)->sum('bonus_amount'),
            'awards' => $results,
        ];
    }

    /**
     * Calculate tier bonuses for a single person
     */
    private function calculateTierBonusesForPerson($performance, $periodStart, $periodEnd)
    {
        $bonuses = [];

        // Revenue-based tiers
        $revenueTiers = BonusTier::active()->byType('revenue')->ordered()->get();
        foreach ($revenueTiers as $tier) {
            if ($performance['total_revenue'] >= $tier->threshold) {
                $amount = $tier->calculateBonus($performance['total_revenue']);
                if ($amount > 0) {
                    $bonuses[] = [
                        'type' => 'revenue',
                        'tier_id' => $tier->id,
                        'tier_name' => $tier->name,
                        'amount' => $amount,
                        'reason' => "Revenue tier: {$tier->name} (Threshold: " . number_format($tier->threshold, 2) . " MMK)",
                    ];
                }
            }
        }

        // Quantity-based tiers
        $quantityTiers = BonusTier::active()->byType('quantity')->ordered()->get();
        foreach ($quantityTiers as $tier) {
            if ($performance['total_quantity'] >= $tier->threshold) {
                $amount = $tier->calculateBonus($performance['total_quantity']);
                if ($amount > 0) {
                    $bonuses[] = [
                        'type' => 'quantity',
                        'tier_id' => $tier->id,
                        'tier_name' => $tier->name,
                        'amount' => $amount,
                        'reason' => "Quantity tier: {$tier->name} (Threshold: " . number_format($tier->threshold) . " items)",
                    ];
                }
            }
        }

        // Commission-based (percentage of all sales)
        $commissionTiers = BonusTier::active()->byType('commission')->ordered()->get();
        foreach ($commissionTiers as $tier) {
            $amount = ($performance['total_revenue'] * $tier->bonus_percentage) / 100;
            if ($amount > 0) {
                $bonuses[] = [
                    'type' => 'commission',
                    'tier_id' => $tier->id,
                    'tier_name' => $tier->name,
                    'amount' => $amount,
                    'reason' => "Commission: {$tier->name} ({$tier->bonus_percentage}% of sales)",
                ];
            }
        }

        return $bonuses;
    }
}
