<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryLevelImpact extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_id',
        'affected_user_id',
        'affected_role',
        'impact_score',
        'impact_notes',
    ];

    protected $casts = [
        'impact_score' => 'decimal:2',
    ];

    public function checklist()
    {
        return $this->belongsTo(EntryLevelChecklist::class, 'checklist_id');
    }

    public function affectedUser()
    {
        return $this->belongsTo(User::class, 'affected_user_id');
    }
}
