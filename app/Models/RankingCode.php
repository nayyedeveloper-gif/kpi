<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RankingCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'position_name',
        'name',
        'guardian_name',
        'guardian_code',
        'branch_code',
        'group_code',
        'position_code',
        'id_code',
        'ranking_id',
    ];

    protected $casts = [
        'branch_code' => 'integer',
        'id_code' => 'integer',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->ranking_id)) {
                $model->ranking_id = static::generateRankingId($model);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty(['guardian_code', 'branch_code', 'group_code', 'position_code', 'id_code'])) {
                $model->ranking_id = static::generateRankingId($model);
            }
        });
    }

    /**
     * Generate a unique ranking ID
     */
    public static function generateRankingId($model)
    {
        $branch = abs($model->branch_code);
        $group = strtoupper(trim($model->group_code));
        $position = strtoupper(trim($model->position_code));
        $idCode = str_pad($model->id_code, 3, '0', STR_PAD_LEFT);
        
        return sprintf('%s-%d%s%s%s', 
            strtoupper(trim($model->guardian_code)),
            $branch,
            $group,
            $position,
            $idCode
        );
    }

    /**
     * Get the validation rules
     */
    public static function rules($id = null)
    {
        return [
            'group_name' => 'required|string|max:10',
            'position_name' => 'required|string|max:50',
            'name' => 'nullable|string|max:100',
            'guardian_name' => 'required|string|max:100',
            'guardian_code' => 'required|string|size:1|regex:/^[A-Za-z]$/',
            'branch_code' => 'required|integer',
            'group_code' => 'required|string|max:2|regex:/^[A-Za-z0-9]+$/|min:1',
            'position_code' => 'required|string|max:3|regex:/^[A-Za-z0-9]+$/|min:1',
            'id_code' => 'required|integer|min:1',
        ];
    }

    /**
     * Get the validation error messages
     */
    public static function validationMessages()
    {
        return [
            'guardian_code.regex' => 'အုပ်ချုပ်သူကုဒ်သည် A-Z စာလုံးတစ်လုံးသာဖြစ်ရပါမည်။',
            'group_code.regex' => 'အဖွဲ့ကုဒ်တွင် စာလုံးနှင့်နံပါတ်များသာပါဝင်ရပါမည်။',
            'position_code.regex' => 'ရာထူးကုဒ်တွင် စာလုံးနှင့်နံပါတ်များသာပါဝင်ရပါမည်။',
            'id_code.integer' => 'ID နံပါတ်သည် နံပါတ်တစ်ခုသာဖြစ်ရပါမည်။',
            'id_code.min' => 'ID နံပါတ်သည် 1 ထက်ကြီးရပါမည်။',
            'branch_code.integer' => 'ဘရန်ချ်ကုဒ်သည် နံပါတ်တစ်ခုသာဖြစ်ရပါမည်။',
        ];
    }

    /**
     * Get the KPI measurements for this ranking code
     */
    public function kpiMeasurements()
    {
        return $this->hasMany(KpiMeasurement::class);
    }

    /**
     * Get the users associated with this ranking code
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}