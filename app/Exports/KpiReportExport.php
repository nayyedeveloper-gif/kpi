<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KpiReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $type;

    public function __construct($data, $type = 'detailed')
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        if ($this->type === 'detailed') {
            return [
                'Date',
                'User',
                'Department',
                'Position',
                'Ready to Sale',
                'Counter Check',
                'Cleanliness',
                'Stock Check',
                'Order Handling',
                'Customer Followup',
                'Total Score',
                'Percentage',
                'Logs Count'
            ];
        }

        return ['Data'];
    }

    public function map($measurement): array
    {
        if ($this->type === 'detailed') {
            return [
                $measurement->measurement_date->format('Y-m-d'),
                $measurement->user->name,
                $measurement->user->department->name ?? 'N/A',
                $measurement->user->position->name ?? 'N/A',
                $measurement->ready_to_sale ? 'Yes' : 'No',
                $measurement->counter_check ? 'Yes' : 'No',
                $measurement->cleanliness ? 'Yes' : 'No',
                $measurement->stock_check ? 'Yes' : 'No',
                $measurement->order_handling ? 'Yes' : 'No',
                $measurement->customer_followup ? 'Yes' : 'No',
                $measurement->total_score . '/6',
                round($measurement->percentage, 2) . '%',
                $measurement->logs->count()
            ];
        }

        return [$measurement];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
