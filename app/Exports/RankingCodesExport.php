<?php

namespace App\Exports;

use App\Models\RankingCode;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RankingCodesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return RankingCode::all();
    }

    /**
     * Column headings
     */
    public function headings(): array
    {
        return [
            'Branch Name',
            'Group Name',
            'Position Name',
            'Name',
            'Guardian Name',
            'ID Code',
            'Ranking ID',
        ];
    }

    /**
     * Map data for each row
     */
    public function map($rankingCode): array
    {
        return [
            $rankingCode->branch_name,
            $rankingCode->group_name,
            $rankingCode->position_name,
            $rankingCode->name,
            $rankingCode->guardian_name ?? '',
            $rankingCode->id_code,
            $rankingCode->ranking_id,
        ];
    }
}

