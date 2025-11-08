<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BonusAward extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_person_id',
        'bonus_configuration_id',
        'period_start',
        'period_end',
        'total_revenue',
        'total_quantity',
        'total_transactions',
        'rank',
        'bonus_amount',
        'bonus_type',
        'reason',
        'status',
        'awarded_at',
        'paid_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_revenue' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
        'awarded_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    public function bonusConfiguration()
    {
        return $this->belongsTo(BonusConfiguration::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForPeriod($query, $start, $end)
    {
        return $query->where('period_start', '>=', $start)
                     ->where('period_end', '<=', $end);
    }
}
