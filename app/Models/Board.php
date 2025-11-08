<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Board extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'share_link',
        'is_public',
        'settings',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'settings' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($board) {
            if (!$board->share_link) {
                $board->share_link = Str::random(32);
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function elements()
    {
        return $this->hasMany(BoardElement::class);
    }

    public function collaborators()
    {
        return $this->hasMany(BoardCollaborator::class);
    }

    public function comments()
    {
        return $this->hasMany(BoardComment::class);
    }

    public function reactions()
    {
        return $this->hasMany(BoardReaction::class);
    }

    public function versions()
    {
        return $this->hasMany(BoardVersion::class);
    }

    public function activeCollaborators()
    {
        return $this->collaborators()
            ->where('last_seen_at', '>=', now()->subMinutes(5));
    }

    public function getShareUrlAttribute()
    {
        return url('/board/' . $this->id . '?share=' . $this->share_link);
    }
}
