<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ranking_code_id',
        'measurement_date',
        'ready_to_sale',
        'counter_check',
        'cleanliness',
        'stock_check',
        'order_handling',
        'customer_followup',
        'total_score',
        'percentage',
        'notes',
        'photo_path',
        'personality_score',
        'performance_score',
        'hospitality_score',
        'customer_follow_up_score',
        'number_of_people',
        'supervised_level_score',
        'personality_kpis',
        'performance_kpis',
        'hospitality_kpis',
        'team_management_kpis',
        'customer_follow_up_kpis',
        'supervised_level_kpis',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'measurement_date' => 'date',
        'ready_to_sale' => 'boolean',
        'counter_check' => 'boolean',
        'cleanliness' => 'boolean',
        'stock_check' => 'boolean',
        'order_handling' => 'boolean',
        'customer_followup' => 'boolean',
        'total_score' => 'integer',
        'percentage' => 'decimal:2',
        'customer_follow_up_score' => 'integer',
        'number_of_people' => 'integer',
        'supervised_level_score' => 'integer',
        'personality_kpis' => 'array',
        'performance_kpis' => 'array',
        'hospitality_kpis' => 'array',
        'team_management_kpis' => 'array',
        'customer_follow_up_kpis' => 'array',
        'supervised_level_kpis' => 'array',
    ];

    protected static function booted()
    {
        static::saving(function ($measurement) {
            // Calculate total_score
            $measurement->total_score = (int)$measurement->ready_to_sale +
                                       (int)$measurement->counter_check +
                                       (int)$measurement->cleanliness +
                                       (int)$measurement->stock_check +
                                       (int)$measurement->order_handling +
                                       (int)$measurement->customer_followup;
            
            // Calculate percentage
            $measurement->percentage = ($measurement->total_score / 6) * 100;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function rankingCode()
    {
        return $this->belongsTo(RankingCode::class);
    }

    public function logs()
    {
        return $this->hasMany(KpiLog::class);
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('measurement_date', $date);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}