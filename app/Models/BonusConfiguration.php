<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BonusConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'period',
        'criteria',
        'bonus_amount',
        'bonus_percentage',
        'rank_limit',
        'minimum_revenue',
        'minimum_quantity',
        'is_active',
        'description',
    ];

    protected $casts = [
        'criteria' => 'array',
        'bonus_amount' => 'decimal:2',
        'bonus_percentage' => 'decimal:2',
        'minimum_revenue' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function bonusAwards()
    {
        return $this->hasMany(BonusAward::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPeriod($query, $period)
    {
        return $query->where('period', $period);
    }
}
