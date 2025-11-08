<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level_type',
        'checker_role_id',
        'target_role_id',
        'cascade_enabled',
        'impact_weights',
        'good_impact',
        'bad_impact',
        'max_cascade_levels',
        'is_active',
        'description',
    ];

    protected $casts = [
        'cascade_enabled' => 'boolean',
        'is_active' => 'boolean',
        'impact_weights' => 'array',
        'good_impact' => 'array',
        'bad_impact' => 'array',
    ];

    /**
     * Get the checker role.
     */
    public function checkerRole()
    {
        return $this->belongsTo(Role::class, 'checker_role_id');
    }

    /**
     * Get the target role.
     */
    public function targetRole()
    {
        return $this->belongsTo(Role::class, 'target_role_id');
    }

    /**
     * Scope for active configurations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific level type.
     */
    public function scopeForLevel($query, $levelType)
    {
        return $query->where('level_type', $levelType);
    }

    /**
     * Get default good impact weights.
     */
    public static function getDefaultGoodImpact()
    {
        return [
            0 => 10.0,  // Direct user (Sales Person)
            1 => 3.0,   // Level 1 (Leader)
            2 => 2.0,   // Level 2 (Supervisor)
            3 => 1.0,   // Level 3 (Assistant Manager)
            4 => 0.5,   // Level 4 (Manager)
        ];
    }

    /**
     * Get default bad impact weights.
     */
    public static function getDefaultBadImpact()
    {
        return [
            0 => -10.0,  // Direct user (Sales Person)
            1 => -5.0,   // Level 1 (Leader)
            2 => -3.0,   // Level 2 (Supervisor)
            3 => -2.0,   // Level 3 (Assistant Manager)
            4 => -1.0,   // Level 4 (Manager)
        ];
    }

    /**
     * Get impact score for a specific hierarchy level.
     */
    public function getImpactScore($hierarchyLevel, $impactType)
    {
        $impacts = $impactType === 'good' ? $this->good_impact : $this->bad_impact;
        
        if (!$impacts || !isset($impacts[$hierarchyLevel])) {
            $defaults = $impactType === 'good' 
                ? self::getDefaultGoodImpact() 
                : self::getDefaultBadImpact();
            return $defaults[$hierarchyLevel] ?? 0;
        }
        
        return $impacts[$hierarchyLevel];
    }
}
