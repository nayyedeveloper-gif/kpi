<div class="p-6 space-y-6">
    @if (session()->has('message'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-green-800 text-sm font-medium">{{ session('message') }}</span>
        </div>
    </div>
    @endif

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">KPI Reports</h1>
                    <p class="text-sm text-gray-600 mt-1">Generate and export performance reports</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button wire:click="print" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Print</span>
                </button>
                <button wire:click="exportPDF" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span>PDF</span>
                </button>
                <button wire:click="exportExcel" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Excel</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Report Type Selection -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center space-x-2 mb-4">
            <button wire:click="$set('reportType', 'summary')" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors font-medium text-sm {{ $reportType === 'summary' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Summary Report
            </button>
            <button wire:click="$set('reportType', 'detailed')" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors font-medium text-sm {{ $reportType === 'detailed' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Detailed Report
            </button>
            <button wire:click="$set('reportType', 'individual')" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors font-medium text-sm {{ $reportType === 'individual' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Individual Report
            </button>
            <button wire:click="$set('reportType', 'department')" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors font-medium text-sm {{ $reportType === 'department' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Department Report
            </button>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" wire:model.live="dateFrom" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" wire:model.live="dateTo" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            @if($reportType === 'individual' || $reportType === 'detailed')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <select wire:model.live="selectedUser" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            @if($reportType === 'department' || $reportType === 'summary' || $reportType === 'detailed')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select wire:model.live="selectedDepartment" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
    </div>

    <!-- Report Content -->
    <div id="printable-report" class="bg-white rounded-lg shadow-lg p-8">
        <!-- Report Header -->
        <div class="border-b-2 border-gray-200 pb-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                @if($reportType === 'summary') Summary Report
                @elseif($reportType === 'detailed') Detailed Report
                @elseif($reportType === 'individual') Individual Performance Report
                @elseif($reportType === 'department') Department Report
                @endif
            </h2>
            <p class="text-gray-600 mt-1">Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
        </div>

        @if($reportType === 'summary')
        <!-- Summary Report -->
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-blue-600 font-medium">Total Measurements</p>
                    <p class="text-3xl font-bold text-blue-900 mt-1">{{ $summaryReport['total_measurements'] }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-green-600 font-medium">Total Users</p>
                    <p class="text-3xl font-bold text-green-900 mt-1">{{ $summaryReport['total_users'] }}</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm text-purple-600 font-medium">Average Score</p>
                    <p class="text-3xl font-bold text-purple-900 mt-1">{{ $summaryReport['avg_score'] }}/6</p>
                </div>
                <div class="bg-orange-50 rounded-lg p-4">
                    <p class="text-sm text-orange-600 font-medium">Average %</p>
                    <p class="text-3xl font-bold text-orange-900 mt-1">{{ $summaryReport['avg_percentage'] }}%</p>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">KPI Breakdown</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($summaryReport['kpi_breakdown'] as $kpi => $count)
                    <div class="border rounded-lg p-4">
                        <p class="text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $kpi)) }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $count }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-green-600 font-medium">Good Logs</p>
                    <p class="text-3xl font-bold text-green-900 mt-1">{{ $summaryReport['good_logs'] }}</p>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm text-red-600 font-medium">Bad Logs</p>
                    <p class="text-3xl font-bold text-red-900 mt-1">{{ $summaryReport['bad_logs'] }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($reportType === 'detailed')
        <!-- Detailed Report -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percentage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Logs</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($detailedReport as $measurement)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $measurement->measurement_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $measurement->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $measurement->user->department->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-green-600">{{ $measurement->total_score }}/6</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-full bg-gray-200 rounded-full h-2 mr-2 max-w-[100px]">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $measurement->percentage }}%"></div>
                                </div>
                                <span class="text-xs font-medium">{{ round($measurement->percentage, 1) }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $measurement->logs->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($reportType === 'individual' && $individualReport)
        <!-- Individual Report -->
        <div class="space-y-6">
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $individualReport['user']->name }}</h3>
                <p class="text-gray-600">{{ $individualReport['user']->position->name ?? 'N/A' }} - {{ $individualReport['user']->department->name ?? 'N/A' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-blue-600 font-medium">Total Measurements</p>
                    <p class="text-3xl font-bold text-blue-900 mt-1">{{ $individualReport['total_measurements'] }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-green-600 font-medium">Average Score</p>
                    <p class="text-3xl font-bold text-green-900 mt-1">{{ $individualReport['avg_score'] }}/6</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm text-purple-600 font-medium">Good Logs</p>
                    <p class="text-3xl font-bold text-purple-900 mt-1">{{ $individualReport['good_logs'] }}</p>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <p class="text-sm text-red-600 font-medium">Bad Logs</p>
                    <p class="text-3xl font-bold text-red-900 mt-1">{{ $individualReport['bad_logs'] }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Daily Performance</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ready to Sale</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Counter Check</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cleanliness</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock Check</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Handling</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer Followup</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($individualReport['measurements'] as $measurement)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $measurement->measurement_date->format('M d') }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-green-600">{{ $measurement->total_score }}/6</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($measurement->ready_to_sale)
                                    <span class="text-green-600">✓</span>
                                    @else
                                    <span class="text-red-600">✗</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($measurement->counter_check)
                                    <span class="text-green-600">✓</span>
                                    @else
                                    <span class="text-red-600">✗</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($measurement->cleanliness)
                                    <span class="text-green-600">✓</span>
                                    @else
                                    <span class="text-red-600">✗</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($measurement->stock_check)
                                    <span class="text-green-600">✓</span>
                                    @else
                                    <span class="text-red-600">✗</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($measurement->order_handling)
                                    <span class="text-green-600">✓</span>
                                    @else
                                    <span class="text-red-600">✗</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($measurement->customer_followup)
                                    <span class="text-green-600">✓</span>
                                    @else
                                    <span class="text-red-600">✗</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if($reportType === 'department' && $departmentReport)
        <!-- Department Report -->
        <div class="space-y-6">
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $departmentReport['department']->name }}</h3>
                <p class="text-gray-600">Total Users: {{ $departmentReport['total_users'] }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Measurements</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percentage</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($departmentReport['users'] as $index => $userData)
                        <tr>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">#{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $userData['user']->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $userData['user']->position->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $userData['measurements_count'] }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-green-600">{{ $userData['avg_score'] }}/6</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2 max-w-[100px]">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $userData['avg_percentage'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium">{{ round($userData['avg_percentage'], 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('print-report', () => {
                window.print();
            });
        });
    </script>
    
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-report, #printable-report * {
                visibility: visible;
            }
            #printable-report {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
    @endpush
</div>
