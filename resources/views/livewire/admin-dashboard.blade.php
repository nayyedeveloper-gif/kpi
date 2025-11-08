<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
                <p class="text-sm text-gray-600 mt-1">Sales Administration System - Last 30 Days Performance</p>
            </div>
        </div>
    </div>

    <!-- Organization Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Total Users</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $overviewStats['total_users'] }}</h3>
            <p class="text-xs text-gray-500 mt-1">Active accounts</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Departments</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $overviewStats['total_departments'] }}</h3>
            <p class="text-xs text-gray-500 mt-1">Organization units</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Positions</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $overviewStats['total_positions'] }}</h3>
            <p class="text-xs text-gray-500 mt-1">Job roles</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-600 mb-1">Branches</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $overviewStats['total_branches'] }}</h3>
            <p class="text-xs text-gray-500 mt-1">Locations</p>
        </div>
    </div>

    <!-- KPI & Sales Stats Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- KPI Stats -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">KPI Performance</h2>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <p class="text-xs font-medium text-gray-600 mb-1">Today</p>
                    <p class="text-xl font-bold text-gray-900">{{ $kpiStats['today_measurements'] }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <p class="text-xs font-medium text-gray-600 mb-1">This Month</p>
                    <p class="text-xl font-bold text-gray-900">{{ $kpiStats['this_month_measurements'] }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <p class="text-xs font-medium text-gray-600 mb-1">Avg Score</p>
                    <p class="text-xl font-bold text-gray-900">{{ $kpiStats['avg_score'] }}/6</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <p class="text-xs font-medium text-gray-600 mb-1">Avg %</p>
                    <p class="text-xl font-bold text-gray-900">{{ $kpiStats['avg_percentage'] }}%</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <p class="text-xs font-medium text-gray-600 mb-1">Good Logs</p>
                    <p class="text-xl font-bold text-green-600">{{ $kpiStats['good_logs'] }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <p class="text-xs font-medium text-gray-600 mb-1">Bad Logs</p>
                    <p class="text-xl font-bold text-red-600">{{ $kpiStats['bad_logs'] }}</p>
                </div>
            </div>
        </div>

        <!-- Sales Stats -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Sales Performance</h2>
            </div>
            <div class="space-y-4">
                <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                    <p class="text-xs font-medium text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($salesStats['total_revenue'], 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">MMK</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs font-medium text-gray-600 mb-1">Transactions</p>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($salesStats['total_transactions']) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs font-medium text-gray-600 mb-1">Items Sold</p>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($salesStats['total_items_sold']) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs font-medium text-gray-600 mb-1">Commission</p>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($salesStats['total_commission'], 0) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-xs font-medium text-gray-600 mb-1">Today Sales</p>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($salesStats['today_revenue'], 0) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bonus Stats -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-6">
            <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h2 class="text-lg font-bold text-gray-900">Bonus Overview</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border-2 border-amber-200 p-6 rounded-xl">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Pending Approval</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($bonusStats['pending_amount'], 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $bonusStats['pending_bonuses'] }} awards • MMK</p>
            </div>
            <div class="bg-white border-2 border-indigo-200 p-6 rounded-xl">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Approved</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($bonusStats['approved_amount'], 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $bonusStats['approved_bonuses'] }} awards • MMK</p>
            </div>
            <div class="bg-white border-2 border-green-200 p-6 rounded-xl">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-gray-600 mb-1">Paid</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($bonusStats['paid_amount'], 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $bonusStats['paid_bonuses'] }} awards • MMK</p>
            </div>
        </div>
    </div>

    <!-- Top Performers & Sales -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top KPI Performers -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Top KPI Performers</h2>
            </div>
            <div class="space-y-3">
                @forelse($topPerformers as $index => $performer)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-bold text-sm">#{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $performer->name }}</p>
                            <p class="text-xs text-gray-500">{{ $performer->department->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900">{{ $performer->avg_score }}/6</p>
                        <p class="text-xs text-gray-500">{{ $performer->avg_percentage }}%</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No data available</p>
                @endforelse
            </div>
        </div>

        <!-- Top Sales Persons -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Top Sales Persons</h2>
            </div>
            <div class="space-y-3">
                @forelse($topSalesPersons as $index => $seller)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-bold text-sm">#{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $seller->name }}</p>
                            <p class="text-xs text-gray-500">{{ $seller->transaction_count }} transactions</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-900">{{ number_format($seller->total_revenue, 0) }}</p>
                        <p class="text-xs text-gray-500">MMK</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No sales data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activities & Department Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Recent Activities</h2>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['message'] }}</p>
                        <p class="text-xs text-gray-500">{{ $activity['details'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity['time']->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No recent activities</p>
                @endforelse
            </div>
        </div>

        <!-- Department Performance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Department Performance</h2>
            </div>
            <div class="space-y-3">
                @forelse($departmentPerformance as $dept)
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <p class="font-bold text-gray-900">{{ $dept->name }}</p>
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full font-medium">{{ $dept->users_count }} users</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Avg Score: <strong class="text-gray-900">{{ $dept->avg_score }}/6</strong></span>
                        <span class="text-gray-600">Percentage: <strong class="text-gray-900">{{ $dept->avg_percentage }}%</strong></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ min($dept->avg_percentage, 100) }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">No department data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
