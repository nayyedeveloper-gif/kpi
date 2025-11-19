<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KpiMeasurement;
use App\Models\User;

class KpiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'kpi_measurement_id',
        'user_id',
        'ranking_code_id',
        'action',
        'kpi_field',
        'status',
        'notes',
        'photo_path',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function kpiMeasurement()
    {
        return $this->belongsTo(KpiMeasurement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeGood($query)
    {
        return $query->where('status', 'good');
    }

    public function scopeBad($query)
    {
        return $query->where('status', 'bad');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('logged_at', today());
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('logged_at', $date);
    }

    public function getKpiFieldNameAttribute()
    {
        $fieldNames = [
            'ready_to_sale' => 'Ready to Sale',
            'counter_check' => 'Counter Check',
            'cleanliness' => 'Cleanliness',
            'stock_check' => 'Stock Check',
            'order_handling' => 'Order Handling',
            'customer_followup' => 'Customer Follow-Up',
        ];

        return $fieldNames[$this->kpi_field] ?? ucwords(str_replace('_', ' ', $this->kpi_field));
    }
}
