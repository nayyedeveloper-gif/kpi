<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'period_date',
        'period_type',
        'overall_score',
        'kpi_score',
        'task_score',
        'quality_score',
        'attendance_score',
        'collaboration_score',
        'tasks_completed',
        'tasks_total',
        'kpis_completed',
        'kpis_total',
        'days_present',
        'days_total',
        'department_rank',
        'company_rank',
    ];

    protected $casts = [
        'period_date' => 'date',
        'overall_score' => 'decimal:2',
        'kpi_score' => 'decimal:2',
        'task_score' => 'decimal:2',
        'quality_score' => 'decimal:2',
        'attendance_score' => 'decimal:2',
        'collaboration_score' => 'decimal:2',
    ];

    /**
     * Get the user that owns the performance score.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate overall score from individual metrics.
     */
    public function calculateOverallScore()
    {
        $weights = [
            'kpi_score' => 0.35,
            'task_score' => 0.25,
            'quality_score' => 0.20,
            'attendance_score' => 0.10,
            'collaboration_score' => 0.10,
        ];

        $this->overall_score = 
            ($this->kpi_score * $weights['kpi_score']) +
            ($this->task_score * $weights['task_score']) +
            ($this->quality_score * $weights['quality_score']) +
            ($this->attendance_score * $weights['attendance_score']) +
            ($this->collaboration_score * $weights['collaboration_score']);

        return $this->overall_score;
    }

    /**
     * Get performance rating based on score.
     */
    public function getRatingAttribute()
    {
        if ($this->overall_score >= 90) return 'Excellent';
        if ($this->overall_score >= 80) return 'Very Good';
        if ($this->overall_score >= 70) return 'Good';
        if ($this->overall_score >= 60) return 'Satisfactory';
        return 'Needs Improvement';
    }

    /**
     * Get rating color.
     */
    public function getRatingColorAttribute()
    {
        if ($this->overall_score >= 90) return 'green';
        if ($this->overall_score >= 80) return 'blue';
        if ($this->overall_score >= 70) return 'yellow';
        if ($this->overall_score >= 60) return 'orange';
        return 'red';
    }

    /**
     * Scope for current month.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereYear('period_date', now()->year)
                    ->whereMonth('period_date', now()->month)
                    ->where('period_type', 'monthly');
    }

    /**
     * Scope for specific period.
     */
    public function scopeForPeriod($query, $date, $type = 'monthly')
    {
        return $query->where('period_date', $date)
                    ->where('period_type', $type);
    }
}
