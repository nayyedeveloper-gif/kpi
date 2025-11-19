<div>
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button wire:click="$dispatch('browser-back')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <div class="flex items-center space-x-4">
                        @if($user->profile_photo)
                            <img src="{{ Storage::url($user->profile_photo) }}" alt="Profile" class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center border-4 border-white shadow-lg">
                                <span class="text-2xl font-bold text-white">{{ substr($user->name, 0, 2) }}</span>
                            </div>
                        @endif
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            @if($user->rankingCode)
                                <p class="text-sm text-blue-600 font-medium">{{ $user->rankingCode->name }} ({{ $user->rankingCode->ranking_id }})</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @if($user->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex space-x-8">
                <button wire:click="setActiveTab('overview')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Overview
                </button>
                <button wire:click="setActiveTab('performance')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'performance' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    KPI Performance
                </button>
                <button wire:click="setActiveTab('activities')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'activities' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Activities
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Overview Tab -->
        @if($activeTab === 'overview')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- User Information -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="text-sm text-gray-900">{{ $user->phone_number ?? 'Not provided' }}</dd>
                        </div>
                        @if($user->rankingCode)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ranking Code</dt>
                            <dd class="text-sm text-gray-900">{{ $user->rankingCode->name }} ({{ $user->rankingCode->ranking_id }})</dd>
                        </div>
                        @endif
                        @if($user->department)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Department</dt>
                            <dd class="text-sm text-gray-900">{{ $user->department->name }}</dd>
                        </div>
                        @endif
                        @if($user->role)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="text-sm text-gray-900">{{ $user->role->display_name }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                            <dd class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Performance Stats -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Performance Statistics</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">{{ $performanceStats->total_measurements ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Total KPI Measurements</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ number_format($performanceStats->avg_score ?? 0, 1) }}</div>
                            <div class="text-sm text-gray-500">Average Score</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">{{ $performanceStats->highest_score ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Highest Score</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-orange-600">{{ $performanceStats->good_measurements ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Good Measurements</div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Performance Chart -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Performance Trend</h3>
                    <div class="space-y-4">
                        @forelse($monthlyPerformance as $month)
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-900">{{ date('M Y', strtotime($month->month . '-01')) }}</div>
                            <div class="flex items-center space-x-3">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-32">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($month->avg_score / 9) * 100 }}%"></div>
                                </div>
                                <div class="text-sm text-gray-600 w-12 text-right">{{ number_format($month->avg_score, 1) }}</div>
                                <div class="text-sm text-gray-500 w-8 text-right">{{ $month->measurement_count }}</div>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">No monthly performance data available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Performance Tab -->
        @if($activeTab === 'performance')
        <div class="space-y-6">
            <!-- KPI Measurements Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">KPI Measurement History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Personality</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hospitality</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($kpiMeasurements as $measurement)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $measurement->measurement_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $measurement->rankingCode->ranking_id ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $measurement->personality_score }}/4
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $measurement->performance_score }}/3
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $measurement->hospitality_score }}/2
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $measurement->total_score >= 6 ? 'bg-green-100 text-green-800' : ($measurement->total_score >= 4 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $measurement->total_score }}/9
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ $measurement->notes ?? 'No notes' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No KPI measurements found for this user.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($kpiMeasurements->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $kpiMeasurements->links() }}
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Activities Tab -->
        @if($activeTab === 'activities')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Activities</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentActivities as $activity)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ ucfirst($activity->action) }}
                                    @if($activity->kpiMeasurement)
                                        - {{ $activity->kpiMeasurement->rankingCode->ranking_id ?? 'Unknown' }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $activity->description ?? 'Activity performed' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $activity->logged_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    No recent activities found for this user.
                </div>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</div>
