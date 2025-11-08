<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BonusTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'threshold',
        'bonus_amount',
        'bonus_percentage',
        'calculation_method',
        'priority',
        'is_active',
        'description',
    ];

    protected $casts = [
        'threshold' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
        'bonus_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority')->orderBy('threshold');
    }

    /**
     * Calculate bonus for given amount
     */
    public function calculateBonus($amount)
    {
        if ($amount < $this->threshold) {
            return 0;
        }

        switch ($this->calculation_method) {
            case 'fixed':
                return $this->bonus_amount;
            case 'percentage':
                return ($amount * $this->bonus_percentage) / 100;
            case 'cumulative':
                // Fixed amount + percentage
                return $this->bonus_amount + (($amount * $this->bonus_percentage) / 100);
            default:
                return $this->bonus_amount;
        }
    }
}
