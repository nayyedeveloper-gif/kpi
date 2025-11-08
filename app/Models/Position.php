<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'hierarchy_level',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hierarchy_level' => 'integer',
    ];

    /**
     * Validation rules for Position
     */
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:positions,name',
            'description' => 'nullable|string',
            'hierarchy_level' => 'required|integer|min:1|max:15',
            'is_active' => 'boolean',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrderedByHierarchy($query)
    {
        return $query->orderBy('hierarchy_level', 'asc');
    }
}