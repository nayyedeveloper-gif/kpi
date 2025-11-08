<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesPerson extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'commission_rate',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commission_rate' => 'float',
    ];

    /**
     * Get the sales transactions for the sales person.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(SalesTransaction::class);
    }
}
