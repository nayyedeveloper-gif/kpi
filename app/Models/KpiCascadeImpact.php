<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiCascadeImpact extends Model
{
    use HasFactory;

    protected $fillable = [
        'kpi_log_id',
        'kpi_measurement_id',
        'source_user_id',
        'affected_user_id',
        'hierarchy_level',
        'hierarchy_position',
        'impact_type',
        'impact_score',
        'weight_multiplier',
        'reason',
        'notification_sent',
        'applied_at',
    ];

    protected $casts = [
        'impact_score' => 'decimal:2',
        'weight_multiplier' => 'decimal:2',
        'notification_sent' => 'boolean',
        'applied_at' => 'datetime',
    ];

    /**
     * Get the KPI log that caused this impact.
     */
    public function kpiLog()
    {
        return $this->belongsTo(KpiLog::class);
    }

    /**
     * Get the KPI measurement.
     */
    public function kpiMeasurement()
    {
        return $this->belongsTo(KpiMeasurement::class);
    }

    /**
     * Get the source user (who got the original good/bad).
     */
    public function sourceUser()
    {
        return $this->belongsTo(User::class, 'source_user_id');
    }

    /**
     * Get the affected user (who received the cascading impact).
     */
    public function affectedUser()
    {
        return $this->belongsTo(User::class, 'affected_user_id');
    }

    /**
     * Scope for good impacts.
     */
    public function scopeGood($query)
    {
        return $query->where('impact_type', 'good');
    }

    /**
     * Scope for bad impacts.
     */
    public function scopeBad($query)
    {
        return $query->where('impact_type', 'bad');
    }

    /**
     * Scope for specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('affected_user_id', $userId);
    }

    /**
     * Scope for specific hierarchy level.
     */
    public function scopeAtLevel($query, $level)
    {
        return $query->where('hierarchy_level', $level);
    }

    /**
     * Scope for pending notifications.
     */
    public function scopePendingNotification($query)
    {
        return $query->where('notification_sent', false);
    }

    /**
     * Mark notification as sent.
     */
    public function markNotificationSent()
    {
        $this->update(['notification_sent' => true]);
    }

    /**
     * Get formatted reason text.
     */
    public function getFormattedReasonAttribute()
    {
        if ($this->reason) {
            return $this->reason;
        }

        $sourceUserName = $this->sourceUser->name ?? 'Unknown';
        $impactText = $this->impact_type === 'good' ? 'good performance' : 'poor performance';
        
        return "Affected due to {$sourceUserName}'s {$impactText} (Level {$this->hierarchy_level})";
    }
}
