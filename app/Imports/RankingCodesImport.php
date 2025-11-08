<?php

namespace App\Imports;

use App\Models\RankingCode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class RankingCodesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Handle different column name variations
        $branchName = $row['branch_name'] ?? $row['branch name'] ?? $row['branch'] ?? null;
        $groupName = $row['group_name'] ?? $row['group name'] ?? $row['group'] ?? null;
        $positionName = $row['position_name'] ?? $row['position name'] ?? $row['position'] ?? null;
        $name = $row['name'] ?? null;
        $guardianName = $row['guardian_name'] ?? $row['guardian name'] ?? $row['guardian'] ?? null;
        $idCode = $row['id_code'] ?? $row['id code'] ?? $row['idcode'] ?? null;
        $rankingId = $row['ranking_id'] ?? $row['ranking id'] ?? $row['rankingid'] ?? null;

        // Skip if required fields are missing
        if (!$branchName || !$groupName || !$positionName || !$name || !$idCode || !$rankingId) {
            return null;
        }

        // Check if ranking_id already exists, update if exists
        $existing = RankingCode::where('ranking_id', $rankingId)->first();
        
        if ($existing) {
            $existing->update([
                'branch_name' => $branchName,
                'group_name' => $groupName,
                'position_name' => $positionName,
                'name' => $name,
                'guardian_name' => $guardianName ?: null,
                'id_code' => $idCode,
            ]);
            return null; // Don't create new model
        }

        return new RankingCode([
            'branch_name' => $branchName,
            'group_name' => $groupName,
            'position_name' => $positionName,
            'name' => $name,
            'guardian_name' => $guardianName ?: null,
            'id_code' => $idCode,
            'ranking_id' => $rankingId,
        ]);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'ranking_id' => 'required|unique:ranking_codes,ranking_id',
        ];
    }
}

