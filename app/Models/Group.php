<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Branch;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'group_type',
        'leader_id',
        'branch_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_user')
            ->withPivot('role_in_group', 'joined_at')
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('group_type', $type);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }
}
