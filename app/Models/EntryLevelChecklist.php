<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\EntryLevelImpact;

class EntryLevelChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'evaluator_id',
        'evaluation_date',
        'personality_score',
        'personality_notes',
        'performance_score',
        'performance_notes',
        'hospitality_score',
        'hospitality_notes',
        'cleaning_score',
        'cleaning_notes',
        'learning_achievement_score',
        'learning_achievement_notes',
        'total_score',
        'status',
        'general_comments',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'total_score' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function impacts()
    {
        return $this->hasMany(EntryLevelImpact::class, 'checklist_id');
    }

    // Calculate total score (count Good/true values, convert to percentage)
    public function calculateTotalScore()
    {
        $goodCount = 0;
        if ($this->personality_score) $goodCount++;
        if ($this->performance_score) $goodCount++;
        if ($this->hospitality_score) $goodCount++;
        if ($this->cleaning_score) $goodCount++;
        if ($this->learning_achievement_score) $goodCount++;
        
        // Convert to percentage (5 areas, each worth 20%)
        return ($goodCount / 5) * 100;
    }

    // Determine status based on score
    public function determineStatus()
    {
        $score = $this->calculateTotalScore();
        return $score >= 60 ? 'compliant' : 'violation';
    }

    // Get score color
    public function getScoreColorAttribute()
    {
        $score = $this->total_score;
        if ($score >= 80) return 'green';
        if ($score >= 60) return 'yellow';
        return 'red';
    }

    // Get score label
    public function getScoreLabelAttribute()
    {
        $score = $this->total_score;
        if ($score >= 90) return 'Excellent';
        if ($score >= 80) return 'Very Good';
        if ($score >= 70) return 'Good';
        if ($score >= 60) return 'Satisfactory';
        return 'Needs Improvement';
    }

    // Scopes
    public function scopeCompliant($query)
    {
        return $query->where('status', 'compliant');
    }

    public function scopeViolation($query)
    {
        return $query->where('status', 'violation');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('evaluation_date', [$startDate, $endDate]);
    }
}
