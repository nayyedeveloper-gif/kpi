<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_id',
        'type',
        'properties',
        'z_index',
        'created_by',
        'locked_by',
    ];

    protected $casts = [
        'properties' => 'array',
        'z_index' => 'integer',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function comments()
    {
        return $this->hasMany(BoardComment::class, 'element_id');
    }

    public function reactions()
    {
        return $this->hasMany(BoardReaction::class, 'element_id');
    }
}
