<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class PerformanceKPI extends Model
{
    protected $fillable = [
        'ranking_code_id',
        'evaluation_date',
        'personality_score',
        'team_management_score',
        'customer_follow_up_score',
        'supervised_level_score',
        'total_score',
        'status',
        'notes',
        'bonus_amount',
        'is_eligible_for_bonus'
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'personality_score' => 'decimal:2',
        'team_management_score' => 'decimal:2',
        'customer_follow_up_score' => 'decimal:2',
        'supervised_level_score' => 'decimal:2',
        'total_score' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
        'is_eligible_for_bonus' => 'boolean',
        'evaluation_date' => 'date',
    ];

    /**
     * Get the ranking code that owns the performance KPI.
     */
    public function rankingCode(): BelongsTo
    {
        return $this->belongsTo(RankingCode::class);
    }

    /**
     * Calculate total score and check bonus eligibility
     */
    public function calculateScores(): void
    {
        // Calculate total score (average of all scores)
        $this->total_score = (
            $this->personality_score +
            $this->team_management_score +
            $this->customer_follow_up_score +
            $this->supervised_level_score
        ) / 4;

        // Check if eligible for bonus (minimum 25% in each category and total > 80%)
        $minScore = 25.00;
        $minTotalForBonus = 80.00;
        $maxBonus = 50000; // 50,000 MMK

        $meetsMinimumScores = (
            $this->personality_score >= $minScore &&
            $this->team_management_score >= $minScore &&
            $this->customer_follow_up_score >= $minScore &&
            $this->supervised_level_score >= $minScore
        );

        $meetsTotalScore = $this->total_score >= $minTotalForBonus;
        
        $this->is_eligible_for_bonus = $meetsMinimumScores && $meetsTotalScore;
        
        // Calculate bonus amount (proportional to score, up to max 50,000)
        if ($this->is_eligible_for_bonus) {
            $this->bonus_amount = min(
                $maxBonus,
                ($this->total_score / 100) * $maxBonus
            );
        } else {
            $this->bonus_amount = 0;
        }
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::saving(function ($kpi) {
            $kpi->calculateScores();
        });
    }
}
