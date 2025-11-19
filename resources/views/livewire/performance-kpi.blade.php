<div class="space-y-6">
    {{-- Success Message --}}
    @if (session()->has('message'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-green-800 text-sm font-medium">{{ session('message') }}</span>
        </div>
    </div>
    @endif

    <!-- Page Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Performance Tracking Results</h2>
                <p class="mt-1 text-sm text-gray-600">View submitted KPI measurements and individual performance</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('kpi.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New KPI
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search by User</label>
                <input type="text" wire:model.live.debounce.300ms="search" id="search"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Search users...">
            </div>
            <div>
                <label for="selectedDate" class="block text-sm font-medium text-gray-700">Filter by Date</label>
                <input type="date" wire:model.live="selectedDate" id="selectedDate"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="selectedUser" class="block text-sm font-medium text-gray-700">Filter by User</label>
                <select wire:model.live="selectedUser" id="selectedUser"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Users</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- KPI Data Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Personality Score</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team Management Score</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Follow-up</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">People Count</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supervised Level</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kpiMeasurements as $measurement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            @if($measurement->rankingCode)
                                                {{ substr($measurement->rankingCode->ranking_id, 0, 2) }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        @if($measurement->rankingCode)
                                            {{ $measurement->rankingCode->ranking_id }}
                                        @else
                                            No Ranking Code Assigned
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        @if($measurement->rankingCode)
                                            {{ $measurement->rankingCode->name }}
                                        @else
                                            Legacy Data
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $measurement->measurement_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $measurement->personality_score ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $measurement->performance_score ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $measurement->customer_follow_up_score }}/10
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $measurement->number_of_people }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $measurement->supervised_level_score }}/5
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="viewDetails({{ $measurement->id }})" class="text-blue-600 hover:text-blue-900 mr-3">View Details</button>
                            <button wire:click="deleteMeasurement({{ $measurement->id }})" 
                                    wire:confirm="Are you sure you want to delete this KPI measurement?" 
                                    class="text-red-600 hover:text-red-900 mr-3">Delete</button>
                            <a href="#" class="text-green-600 hover:text-green-900">Export</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No KPI measurements found. <a href="{{ route('kpi.index') }}" class="text-blue-600 hover:text-blue-900">Submit your first KPI measurement</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Details Modal -->
        @if($showDetailsModal && $selectedMeasurement)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="my-modal">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">KPI Measurement Details</h3>
                        <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Basic Information -->
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-3">Basic Information</h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Date:</span>
                                                <span class="font-medium">{{ $selectedMeasurement->measurement_date->format('M d, Y') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Ranking Code:</span>
                                                <span class="font-medium">
                                                    @if($selectedMeasurement->rankingCode)
                                                        {{ $selectedMeasurement->rankingCode->ranking_id }} ({{ $selectedMeasurement->rankingCode->name }})
                                                    @else
                                                        No Ranking Code Assigned
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Entered By:</span>
                                                <span class="font-medium">
                                                    @if($selectedMeasurement->user)
                                                        <a href="{{ route('users.profile', $selectedMeasurement->user) }}" 
                                                           class="text-blue-600 hover:text-blue-800 underline">
                                                            {{ $selectedMeasurement->user->name }}
                                                        </a>
                                                    @else
                                                        Unknown User
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Number of People:</span>
                                                <span class="font-medium">{{ $selectedMeasurement->number_of_people }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Customer Follow-up Score:</span>
                                                <span class="font-medium">{{ $selectedMeasurement->customer_follow_up_score }}/10</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Supervised Level Score:</span>
                                                <span class="font-medium">{{ $selectedMeasurement->supervised_level_score }}/5</span>
                                            </div>
                                        </div>
                                    </div>

                                <!-- KPI Scores Summary -->
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-3">KPI Scores Summary</h4>
                                    <div class="space-y-3">
                                        <div class="bg-purple-50 p-3 rounded-lg">
                                            <div class="flex justify-between items-center">
                                                <span class="text-purple-700 font-medium">Personality:</span>
                                                <span class="text-lg font-bold text-purple-800">{{ $selectedMeasurement->personality_score ?? 'N/A' }}</span>
                                            </div>
                                            <div class="text-xs text-purple-600 mt-1">Out of 4 points</div>
                                        </div>
                                        <div class="bg-blue-50 p-3 rounded-lg">
                                            <div class="flex justify-between items-center">
                                                <span class="text-blue-700 font-medium">Team Management:</span>
                                                <span class="text-lg font-bold text-blue-800">{{ $selectedMeasurement->performance_score ?? 'N/A' }}</span>
                                            </div>
                                            <div class="text-xs text-blue-600 mt-1">Out of 21 points</div>
                                        </div>
                                        <div class="bg-green-50 p-3 rounded-lg">
                                            <div class="flex justify-between items-center">
                                                <span class="text-green-700 font-medium">Customer Follow-up:</span>
                                                <span class="text-lg font-bold text-green-800">{{ $selectedMeasurement->customer_follow_up_score ?? 'N/A' }}/10</span>
                                            </div>
                                            <div class="text-xs text-green-600 mt-1">Manual score</div>
                                        </div>
                                        <div class="bg-orange-50 p-3 rounded-lg">
                                            <div class="flex justify-between items-center">
                                                <span class="text-orange-700 font-medium">Supervised Level:</span>
                                                <span class="text-lg font-bold text-orange-800">{{ $selectedMeasurement->supervised_level_score ?? 'N/A' }}/5</span>
                                            </div>
                                            <div class="text-xs text-orange-600 mt-1">Supervisor evaluation</div>
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <!-- KPI Details -->
                                @if($selectedMeasurement->personality_kpis || $selectedMeasurement->team_management_kpis || $selectedMeasurement->customer_follow_up_kpis || $selectedMeasurement->supervised_level_kpis)
                                <div class="mt-6">
                                    <h4 class="font-semibold text-gray-800 mb-3">Detailed KPIs</h4>
                                    
                                    <!-- Personality KPIs -->
                                    @if($selectedMeasurement->personality_kpis && is_array($selectedMeasurement->personality_kpis))
                                    <div class="mb-4">
                                        <h5 class="font-medium text-gray-700 mb-2">Personality KPIs (4 items)</h5>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($selectedMeasurement->personality_kpis as $key => $kpi)
                                            <div class="flex justify-between items-center bg-purple-50 p-2 rounded text-sm">
                                                <span>{{ $kpi['label'] ?? $key }}</span>
                                                <span class="{{ isset($kpi['checked']) && $kpi['checked'] ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ isset($kpi['checked']) && $kpi['checked'] ? '✓' : '✗' }}
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Team Management KPIs -->
                                    @if($selectedMeasurement->team_management_kpis && is_array($selectedMeasurement->team_management_kpis))
                                    <div class="mb-4">
                                        <h5 class="font-medium text-gray-700 mb-2">Team Management KPIs (21 items)</h5>
                                        @php
                                            $groupedKpis = [];
                                            foreach($selectedMeasurement->team_management_kpis as $key => $kpi) {
                                                $category = $kpi['category'] ?? 'General';
                                                if (!isset($groupedKpis[$category])) {
                                                    $groupedKpis[$category] = [];
                                                }
                                                $groupedKpis[$category][$key] = $kpi;
                                            }
                                        @endphp
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @foreach($groupedKpis as $category => $kpis)
                                            <div class="bg-blue-50 p-3 rounded-lg">
                                                <h6 class="font-medium text-blue-800 mb-2 text-sm">{{ $category }}</h6>
                                                <div class="space-y-1">
                                                    @foreach($kpis as $key => $kpi)
                                                    <div class="flex justify-between items-center text-xs">
                                                        <span class="text-gray-700">{{ $kpi['label'] ?? $key }}</span>
                                                        <span class="{{ isset($kpi['checked']) && $kpi['checked'] ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ isset($kpi['checked']) && $kpi['checked'] ? '✓' : '✗' }}
                                                        </span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Customer Follow-Up KPIs -->
                                    @if($selectedMeasurement->customer_follow_up_kpis && is_array($selectedMeasurement->customer_follow_up_kpis))
                                    <div class="mb-4">
                                        <h5 class="font-medium text-gray-700 mb-2">Customer Follow-Up KPIs</h5>
                                        <div class="bg-green-50 p-3 rounded-lg">
                                            @foreach($selectedMeasurement->customer_follow_up_kpis as $key => $kpi)
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-700">{{ $kpi['label'] ?? $key }}</span>
                                                <span class="{{ isset($kpi['checked']) && $kpi['checked'] ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ isset($kpi['checked']) && $kpi['checked'] ? '✓' : '✗' }}
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Supervised Level KPIs -->
                                    @if($selectedMeasurement->supervised_level_kpis && is_array($selectedMeasurement->supervised_level_kpis))
                                    <div class="mb-4">
                                        <h5 class="font-medium text-gray-700 mb-2">Supervised Level KPIs</h5>
                                        <div class="bg-orange-50 p-3 rounded-lg">
                                            @foreach($selectedMeasurement->supervised_level_kpis as $key => $kpi)
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-700">{{ $kpi['label'] ?? $key }}</span>
                                                <span class="{{ isset($kpi['checked']) && $kpi['checked'] ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ isset($kpi['checked']) && $kpi['checked'] ? '✓' : '✗' }}
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <!-- Logs -->
                                @if($selectedMeasurement->logs && $selectedMeasurement->logs->count() > 0)
                                <div class="mt-6">
                                    <h4 class="font-semibold text-gray-800 mb-3">Recent Logs</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">KPI Field</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($selectedMeasurement->logs->take(5) as $log)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $log->logged_at ? $log->logged_at->format('M d, Y H:i') : 'N/A' }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $log->kpi_field }}</td>
                                                    <td class="px-4 py-2 text-sm">
                                                        <span class="px-2 py-1 text-xs rounded-full {{ $log->status === 'good' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ ucfirst($log->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $log->notes ?? 'N/A' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif

                                <!-- Notes -->
                                @if($selectedMeasurement->notes)
                                <div class="mt-6">
                                    <h4 class="font-semibold text-gray-800 mb-2">Notes</h4>
                                    <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $selectedMeasurement->notes }}</p>
                                </div>
                                @endif

                                <!-- Photo -->
                                @if($selectedMeasurement->photo_path)
                                <div class="mt-6">
                                    <h4 class="font-semibold text-gray-800 mb-2">Photo</h4>
                                    <div class="bg-gray-50 p-3 rounded">
                                        <img src="{{ Storage::url($selectedMeasurement->photo_path) }}" 
                                             alt="KPI Photo" 
                                             class="max-w-full h-auto rounded-lg shadow-sm">
                                        <p class="text-xs text-gray-500 mt-2">
                                            Uploaded at: {{ $selectedMeasurement->created_at->format('M d, Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Pagination -->
        @if($kpiMeasurements->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                @if($kpiMeasurements->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                        Previous
                    </span>
                @else
                    <a href="{{ $kpiMeasurements->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                @endif

                @if($kpiMeasurements->hasMorePages())
                    <a href="{{ $kpiMeasurements->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                @else
                    <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                        Next
                    </span>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $kpiMeasurements->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $kpiMeasurements->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $kpiMeasurements->total() }}</span>
                        results
                    </p>
                </div>
                <div>
                    {{ $kpiMeasurements->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
