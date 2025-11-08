<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Department;
use App\Models\Group;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'location',
        'phone',
        'email',
        'manager_id',
        'department_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
