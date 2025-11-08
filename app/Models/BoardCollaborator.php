<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardCollaborator extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_id',
        'user_id',
        'role',
        'last_seen_at',
        'cursor_position',
        'cursor_color',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'cursor_position' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collaborator) {
            if (!$collaborator->cursor_color) {
                $collaborator->cursor_color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            }
        });
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive()
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }
}
