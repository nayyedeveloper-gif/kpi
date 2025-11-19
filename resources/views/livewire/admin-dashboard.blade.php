<div class="p-6 space-y-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">System Dashboard</h1>
                    <p class="text-sm text-gray-600 mt-1">KPI & Sales Performance Overview - Last {{ $this->dateRange }} Days</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <select wire:model.live="dateRange" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 90 days</option>
                    <option value="365">Last year</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-medium text-gray-500">Active Today</p>
                    <p class="text-lg font-bold text-indigo-600">{{ $overviewStats['active_today'] }}</p>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Ranking Codes</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($overviewStats['total_ranking_codes']) }}</h3>
            <p class="text-xs text-gray-500 mt-1">Active personnel</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-medium text-gray-500">Total Records</p>
                    <p class="text-lg font-bold text-blue-600">{{ number_format($overviewStats['total_kpi_measurements']) }}</p>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">KPI Measurements</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($kpiStats['total_measurements']) }}</h3>
            <p class="text-xs text-gray-500 mt-1">Performance records</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-medium text-gray-500">Today's Sales</p>
                    <p class="text-lg font-bold text-emerald-600">{{ number_format($salesStats['today_revenue'], 0) }}</p>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Sales Transactions</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($overviewStats['total_sales_transactions']) }}</h3>
            <p class="text-xs text-gray-500 mt-1">Total transactions</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div class="text-right">
                    <p class="text-xs font-medium text-gray-500">Avg Score</p>
                    <p class="text-lg font-bold text-purple-600">{{ $kpiStats['avg_score'] }}/6</p>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Performance Rating</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $kpiStats['avg_percentage'] }}%</h3>
            <p class="text-xs text-gray-500 mt-1">Average performance</p>
        </div>
    </div>

    <!-- KPI & Sales Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- KPI Performance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">KPI Performance Metrics</h2>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <p class="text-xs font-medium text-blue-600 mb-1">Today</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $kpiStats['today_measurements'] }}</p>
                    <p class="text-xs text-blue-500">measurements</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <p class="text-xs font-medium text-blue-600 mb-1">This Month</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $kpiStats['this_month_measurements'] }}</p>
                    <p class="text-xs text-blue-500">measurements</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                    <p class="text-xs font-medium text-green-600 mb-1">Positive Logs</p>
                    <p class="text-xl font-bold text-green-600">{{ number_format($kpiStats['good_logs']) }}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                    <p class="text-xs font-medium text-red-600 mb-1">Issues Logged</p>
                    <p class="text-xl font-bold text-red-600">{{ number_format($kpiStats['bad_logs']) }}</p>
                </div>
            </div>
        </div>

        <!-- Sales Performance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <svg class="w-6 h-6 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Sales Performance Metrics</h2>
            </div>
            <div class="space-y-4">
                <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm font-medium text-emerald-600">Total Revenue</p>
                        <p class="text-sm font-bold text-emerald-700">{{ number_format($salesStats['total_revenue'], 0) }} MMK</p>
                    </div>
                    <div class="w-full bg-emerald-200 rounded-full h-2">
                        <div class="bg-emerald-600 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs font-medium text-gray-600 mb-1">Transactions</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($salesStats['total_transactions']) }}</p>
                        <p class="text-xs text-gray-500">total sales</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs font-medium text-gray-600 mb-1">Avg Transaction</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($salesStats['avg_transaction'], 0) }}</p>
                        <p class="text-xs text-gray-500">MMK per sale</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs font-medium text-gray-600 mb-1">Items Sold</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($salesStats['total_items_sold'], 1) }}</p>
                        <p class="text-xs text-gray-500">total units</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs font-medium text-gray-600 mb-1">Commission</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($salesStats['total_commission'], 0) }}</p>
                        <p class="text-xs text-gray-500">MMK earned</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top KPI Performers -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Top KPI Performers</h2>
                <span class="ml-auto text-xs text-gray-500 bg-purple-100 px-2 py-1 rounded-full">Ranking Codes</span>
            </div>
            <div class="space-y-3">
                @forelse($topPerformers as $index => $performer)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 border border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                            #{{ $index + 1 }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $performer->name ?: $performer->ranking_id }}</p>
                            <p class="text-xs text-gray-500">{{ $performer->position_name }} • {{ $performer->measurement_count }} measurements</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900">{{ $performer->avg_score }}/6</p>
                        <p class="text-xs text-gray-500">{{ $performer->avg_percentage }}% avg</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-sm">No KPI data available</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Top Sales Persons -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <svg class="w-6 h-6 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Top Sales Performers</h2>
                <span class="ml-auto text-xs text-gray-500 bg-emerald-100 px-2 py-1 rounded-full">Sales Team</span>
            </div>
            <div class="space-y-3">
                @forelse($topSalesPersons as $index => $seller)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 border border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                            #{{ $index + 1 }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $seller['sales_person'] ? $seller['sales_person']->name : 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $seller['transaction_count'] }} transactions • Avg: {{ number_format($seller['avg_transaction'], 0) }} MMK</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900">{{ number_format($seller['total_revenue'], 0) }}</p>
                        <p class="text-xs text-gray-500">MMK total</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm">No sales data available</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-6">
            <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <h2 class="text-lg font-bold text-gray-900">Recent Activities</h2>
            <span class="ml-auto text-xs text-gray-500 bg-indigo-100 px-2 py-1 rounded-full">Live Feed</span>
        </div>
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @forelse($recentActivities as $activity)
            <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 border border-gray-100">
                <div class="w-10 h-10 rounded-lg bg-{{ $activity['color'] }}-100 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <span class="text-{{ $activity['color'] }}-600 text-lg">{{ $activity['icon'] }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $activity['message'] }}</p>
                    <p class="text-xs text-gray-600 mt-1">{{ $activity['details'] }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $activity['time']->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm">No recent activities</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
