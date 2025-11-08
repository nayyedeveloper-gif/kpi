<div class="p-6 space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">KPI Analytics</h1>
                    <p class="text-sm text-gray-600 mt-1">Performance insights and behavior analysis</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button wire:click="$set('viewType', 'overview')" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors font-medium text-sm {{ $viewType === 'overview' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Overview
                </button>
                <button wire:click="$set('viewType', 'comparison')" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors font-medium text-sm {{ $viewType === 'comparison' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Compare
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <svg class="w-5 h-5 text-gray-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <h3 class="text-base font-semibold text-gray-900">Date Range & Filters</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" wire:model.live="dateFrom" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" wire:model.live="dateTo" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select wire:model.live="selectedDepartment" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if($viewType === 'overview')
    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Total Measurements</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $overviewData['total_measurements'] }}</h3>
            <p class="text-xs text-gray-500 mt-1">All KPI logs</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Average Score</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $overviewData['avg_score'] }}/6</h3>
            <p class="text-xs text-gray-500 mt-1">Overall performance</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Good Logs</p>
            <h3 class="text-2xl font-bold text-green-600">{{ $overviewData['good_logs'] }}</h3>
            <p class="text-xs text-gray-500 mt-1">Positive behaviors</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Bad Logs</p>
            <h3 class="text-2xl font-bold text-red-600">{{ $overviewData['bad_logs'] }}</h3>
            <p class="text-xs text-gray-500 mt-1">Needs improvement</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Daily Trend Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-900">Daily Performance Trend</h3>
            </div>
            <div style="position: relative; height: 200px;">
                <canvas id="dailyTrendChart"></canvas>
            </div>
        </div>

        <!-- KPI Breakdown Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-900">KPI Breakdown - Success Rate</h3>
            </div>
            <div class="mb-4 grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach($kpiBreakdown as $key => $item)
                <div class="text-xs">
                    <span class="font-semibold text-gray-700">{{ $item['name'] }}:</span>
                    <span class="text-blue-600">{{ $item['count'] ?? 0 }} / {{ $overviewData['total_measurements'] }}</span>
                    <span class="text-gray-500">({{ $item['percentage'] }}%)</span>
                </div>
                @endforeach
            </div>
            <div style="position: relative; height: 200px;">
                <canvas id="kpiBreakdownChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
            </svg>
            <h3 class="text-lg font-bold text-gray-900">Top Performers</h3>
        </div>
        @if($topPerformers->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Measurements</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($topPerformers as $index => $performer)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm font-bold {{ $index === 0 ? 'bg-yellow-100 text-yellow-800' : ($index === 1 ? 'bg-gray-100 text-gray-800' : ($index === 2 ? 'bg-orange-100 text-orange-800' : 'bg-blue-50 text-blue-700')) }}">
                                #{{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $performer->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $performer->department->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $performer->position->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-green-600">{{ round($performer->avg_score ?? 0, 2) }}/6</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $performer->kpi_measurements_count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No performance data</h3>
            <p class="mt-1 text-sm text-gray-500">No measurements found for the selected date range.</p>
        </div>
        @endif
    </div>
    @endif

    @if($viewType === 'comparison')
    <!-- User Selection for Comparison -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-lg font-bold text-gray-900">Select Users to Compare</h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($users as $user)
            <label class="flex items-center space-x-2 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                <input type="checkbox" wire:model.live="selectedUsers" value="{{ $user->id }}" class="rounded text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm">{{ $user->name }}</span>
            </label>
            @endforeach
        </div>
    </div>

    @if($userComparison->isNotEmpty())
    <!-- Comparison Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3 class="text-lg font-bold text-gray-900">User Comparison</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Measurements</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percentage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Good Logs</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bad Logs</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($userComparison as $comparison)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $comparison['user']->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $comparison['user']->department->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $comparison['total_measurements'] }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-green-600">{{ $comparison['avg_score'] }}/6</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ min($comparison['avg_percentage'] ?? 0, 100) }}%"></div>
                                </div>
                                <span class="text-xs font-medium">{{ round($comparison['avg_percentage'] ?? 0, 1) }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-green-600 font-semibold">{{ $comparison['good_logs'] }}</td>
                        <td class="px-6 py-4 text-sm text-red-600 font-semibold">{{ $comparison['bad_logs'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No users selected</h3>
            <p class="mt-1 text-sm text-gray-500">Select users above to compare their performance.</p>
        </div>
    </div>
    @endif
    @endif

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Store chart instances globally to prevent re-initialization
        let dailyTrendChart = null;
        let kpiBreakdownChart = null;

        function initCharts() {
            // Daily Trend Chart
            const dailyTrendCtx = document.getElementById('dailyTrendChart');
            if (dailyTrendCtx) {
                // Destroy existing chart if it exists
                if (dailyTrendChart) {
                    dailyTrendChart.destroy();
                }

                const dailyData = @json($dailyTrend);
                dailyTrendChart = new Chart(dailyTrendCtx, {
                    type: 'line',
                    data: {
                        labels: dailyData.map(d => d.date),
                        datasets: [{
                            label: 'Average Score',
                            data: dailyData.map(d => d.avg_score),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 6
                            }
                        }
                    }
                });
            }

            // KPI Breakdown Chart
            const kpiBreakdownCtx = document.getElementById('kpiBreakdownChart');
            if (kpiBreakdownCtx) {
                // Destroy existing chart if it exists
                if (kpiBreakdownChart) {
                    kpiBreakdownChart.destroy();
                }

                const kpiData = @json($kpiBreakdown);
                const kpiValues = Object.values(kpiData);
                
                kpiBreakdownChart = new Chart(kpiBreakdownCtx, {
                    type: 'bar',
                    data: {
                        labels: kpiValues.map(d => d.name),
                        datasets: [{
                            label: 'Success Rate (%)',
                            data: kpiValues.map(d => d.percentage || 0),
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(236, 72, 153, 0.8)',
                                'rgba(34, 197, 94, 0.8)'
                            ],
                            borderColor: [
                                'rgb(59, 130, 246)',
                                'rgb(16, 185, 129)',
                                'rgb(245, 158, 11)',
                                'rgb(139, 92, 246)',
                                'rgb(236, 72, 153)',
                                'rgb(34, 197, 94)'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const item = kpiValues[context.dataIndex];
                                        return [
                                            'Success Rate: ' + (item.percentage || 0) + '%',
                                            'Count: ' + (item.count || 0) + ' completed'
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Initialize charts on page load
        document.addEventListener('DOMContentLoaded', initCharts);

        // Re-initialize charts after Livewire updates
        document.addEventListener('livewire:navigated', initCharts);
        
        // For Livewire 3
        Livewire.hook('morph.updated', ({ el, component }) => {
            initCharts();
        });
    </script>
    @endpush
</div>
