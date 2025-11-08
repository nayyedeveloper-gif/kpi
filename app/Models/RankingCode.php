<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_name',
        'group_name',
        'position_name',
        'name',
        'guardian_name',
        'id_code',
        'ranking_id',
    ];

    protected $casts = [
        //
    ];

    /**
     * Get the validation rules
     */
    public static function rules($id = null)
    {
        $uniqueRule = $id 
            ? 'unique:ranking_codes,ranking_id,' . $id 
            : 'unique:ranking_codes,ranking_id';

        return [
            'branch_name' => 'required|string|max:255',
            'group_name' => 'required|string|max:255',
            'position_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'id_code' => 'required|string|max:50',
            'ranking_id' => 'required|string|max:255|' . $uniqueRule,
        ];
    }
}