<?php

namespace App\Livewire;

use App\Models\KpiMeasurement;
use App\Models\KpiLog;
use App\Models\User;
use App\Models\Department;
use App\Exports\KpiReportExport;
use Livewire\Component;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class KpiReports extends Component
{
    public $reportType = 'summary'; // summary, detailed, individual, department
    public $dateFrom;
    public $dateTo;
    public $selectedUser = '';
    public $selectedDepartment = '';
    public $format = 'view'; // view, pdf, excel
    
    public $users;
    public $departments;

    public function mount()
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
        $this->users = User::active()->with(['department', 'position'])->get();
        $this->departments = Department::active()->get();
    }

    public function getSummaryReportProperty()
    {
        $measurements = KpiMeasurement::with('user')
            ->whereBetween('measurement_date', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedDepartment, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('department_id', $this->selectedDepartment);
                });
            })
            ->get();

        return [
            'total_measurements' => $measurements->count(),
            'total_users' => $measurements->pluck('user_id')->unique()->count(),
            'avg_score' => round($measurements->avg('total_score'), 2),
            'avg_percentage' => round($measurements->avg(function ($m) {
                return $m->percentage;
            }), 2),
            'kpi_breakdown' => [
                'ready_to_sale' => $measurements->where('ready_to_sale', true)->count(),
                'counter_check' => $measurements->where('counter_check', true)->count(),
                'cleanliness' => $measurements->where('cleanliness', true)->count(),
                'stock_check' => $measurements->where('stock_check', true)->count(),
                'order_handling' => $measurements->where('order_handling', true)->count(),
                'customer_followup' => $measurements->where('customer_followup', true)->count(),
            ],
            'good_logs' => KpiLog::good()
                ->whereBetween('logged_at', [$this->dateFrom, $this->dateTo])
                ->count(),
            'bad_logs' => KpiLog::bad()
                ->whereBetween('logged_at', [$this->dateFrom, $this->dateTo])
                ->count(),
        ];
    }

    public function getDetailedReportProperty()
    {
        return KpiMeasurement::with(['user.department', 'user.position', 'logs'])
            ->whereBetween('measurement_date', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedDepartment, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('department_id', $this->selectedDepartment);
                });
            })
            ->when($this->selectedUser, function ($query) {
                $query->where('user_id', $this->selectedUser);
            })
            ->orderBy('measurement_date', 'desc')
            ->get();
    }

    public function getIndividualReportProperty()
    {
        if (!$this->selectedUser) {
            return null;
        }

        $user = User::with(['department', 'position'])->find($this->selectedUser);
        $measurements = $user->kpiMeasurements()
            ->whereBetween('measurement_date', [$this->dateFrom, $this->dateTo])
            ->get();

        return [
            'user' => $user,
            'total_measurements' => $measurements->count(),
            'avg_score' => round($measurements->avg('total_score'), 2),
            'avg_percentage' => round($measurements->avg(function ($m) {
                return $m->percentage;
            }), 2),
            'measurements' => $measurements,
            'good_logs' => $user->kpiLogs()->good()
                ->whereBetween('logged_at', [$this->dateFrom, $this->dateTo])
                ->count(),
            'bad_logs' => $user->kpiLogs()->bad()
                ->whereBetween('logged_at', [$this->dateFrom, $this->dateTo])
                ->count(),
        ];
    }

    public function getDepartmentReportProperty()
    {
        if (!$this->selectedDepartment) {
            return null;
        }

        $department = Department::find($this->selectedDepartment);
        $users = User::where('department_id', $this->selectedDepartment)
            ->active()
            ->with(['position', 'kpiMeasurements' => function ($query) {
                $query->whereBetween('measurement_date', [$this->dateFrom, $this->dateTo]);
            }])
            ->get();

        return [
            'department' => $department,
            'total_users' => $users->count(),
            'users' => $users->map(function ($user) {
                $measurements = $user->kpiMeasurements;
                return [
                    'user' => $user,
                    'measurements_count' => $measurements->count(),
                    'avg_score' => round($measurements->avg('total_score'), 2),
                    'avg_percentage' => round($measurements->avg(function ($m) {
                        return $m->percentage;
                    }), 2),
                ];
            })->sortByDesc('avg_score'),
        ];
    }

    public function exportPDF()
    {
        $data = $this->getReportData();
        
        $pdf = Pdf::loadView('reports.pdf', [
            'reportType' => $this->reportType,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'data' => $data,
        ]);

        $filename = 'kpi-report-' . $this->reportType . '-' . date('Y-m-d') . '.pdf';
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function exportExcel()
    {
        $data = $this->detailedReport;
        
        $filename = 'kpi-report-' . $this->reportType . '-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new KpiReportExport($data, $this->reportType), $filename);
    }

    private function getReportData()
    {
        switch ($this->reportType) {
            case 'summary':
                return $this->summaryReport;
            case 'detailed':
                return $this->detailedReport;
            case 'individual':
                return $this->individualReport;
            case 'department':
                return $this->departmentReport;
            default:
                return [];
        }
    }

    public function print()
    {
        $this->dispatch('print-report');
    }

    public function render()
    {
        $data = [
            'summaryReport' => $this->summaryReport,
            'detailedReport' => $this->detailedReport,
            'individualReport' => $this->individualReport,
            'departmentReport' => $this->departmentReport,
        ];

        return view('livewire.kpi-reports', $data)->layout('layouts.app');
    }
}
