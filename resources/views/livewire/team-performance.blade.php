<div class="p-6 space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Team Performance Board</h1>
                    <p class="text-sm text-gray-600 mt-1">Visual overview of team member performance</p>
                </div>
            </div>
            @if(count($selectedUsers) >= 2)
            <button wire:click="compareSelected" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Compare ({{ count($selectedUsers) }})</span>
            </button>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <svg class="w-5 h-5 text-gray-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <h3 class="text-base font-semibold text-gray-900">Filters</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select wire:model.live="selectedDepartment" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select wire:model.live="sortBy" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="performance">Performance</option>
                    <option value="name">Name</option>
                    <option value="position">Position</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <select wire:model.live="dateRange" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="60">Last 60 days</option>
                    <option value="90">Last 90 days</option>
                </select>
            </div>
            <div class="flex items-end">
                @if(count($selectedUsers) > 0)
                <button wire:click="clearSelection" class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium text-sm">
                    Clear Selection
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-center space-x-8">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">Excellent (76-100%)</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">Average (51-75%)</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">Needs Improvement (0-50%)</span>
            </div>
        </div>
    </div>

    <!-- Team Members Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @forelse($teamMembers as $member)
        <div wire:click="toggleUserSelection({{ $member['user']->id }})" 
             class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 cursor-pointer transition-all hover:shadow-md {{ in_array($member['user']->id, $selectedUsers) ? 'ring-2 ring-indigo-500' : '' }} {{ $member['performance_level'] === 'green' ? 'border-t-4 border-green-500' : ($member['performance_level'] === 'yellow' ? 'border-t-4 border-yellow-500' : 'border-t-4 border-red-500') }}">
            
            <!-- Avatar Circle -->
            <div class="flex justify-center mb-4">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg {{ $member['performance_level'] === 'green' ? 'bg-gradient-to-br from-green-400 to-green-600' : ($member['performance_level'] === 'yellow' ? 'bg-gradient-to-br from-yellow-400 to-yellow-600' : 'bg-gradient-to-br from-red-400 to-red-600') }}">
                        {{ strtoupper(substr($member['user']->name, 0, 2)) }}
                    </div>
                    @if(in_array($member['user']->id, $selectedUsers))
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Name & Position -->
            <div class="text-center mb-3">
                <h3 class="font-bold text-gray-900 text-lg">{{ $member['user']->name }}</h3>
                <p class="text-sm text-gray-600">{{ $member['user']->position->name ?? 'N/A' }}</p>
                <p class="text-xs text-gray-500">{{ $member['user']->department->name ?? 'N/A' }}</p>
            </div>

            <!-- Score -->
            <div class="text-center mb-3">
                <div class="text-3xl font-bold {{ $member['performance_level'] === 'green' ? 'text-green-600' : ($member['performance_level'] === 'yellow' ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ $member['avg_score'] }}<span class="text-lg">/6</span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mb-3">
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="h-3 rounded-full {{ $member['performance_level'] === 'green' ? 'bg-green-500' : ($member['performance_level'] === 'yellow' ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $member['avg_percentage'] }}%"></div>
                </div>
                <p class="text-center text-sm font-semibold mt-1 {{ $member['performance_level'] === 'green' ? 'text-green-600' : ($member['performance_level'] === 'yellow' ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ $member['avg_percentage'] }}%
                </p>
            </div>

            <!-- Logs Stats -->
            <div class="flex justify-between text-xs border-t pt-3">
                <div class="text-center flex-1">
                    <p class="text-green-600 font-bold text-lg">{{ $member['good_logs'] }}</p>
                    <p class="text-gray-500">Good</p>
                </div>
                <div class="text-center flex-1 border-l">
                    <p class="text-red-600 font-bold text-lg">{{ $member['bad_logs'] }}</p>
                    <p class="text-gray-500">Bad</p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-gray-500">No team members found</p>
        </div>
        @endforelse
    </div>

    <!-- Compare Modal -->
    @if($showCompareModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Compare Team Members</h3>
                <button wire:click="closeCompareModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Performance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Good Logs</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bad Logs</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($teamMembers->whereIn('user.id', $selectedUsers) as $member)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold {{ $member['performance_level'] === 'green' ? 'bg-green-500' : ($member['performance_level'] === 'yellow' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                        {{ strtoupper(substr($member['user']->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">{{ $member['user']->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $member['user']->position->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $member['performance_level'] === 'green' ? 'text-green-600' : ($member['performance_level'] === 'yellow' ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $member['avg_score'] }}/6
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="h-2 rounded-full {{ $member['performance_level'] === 'green' ? 'bg-green-500' : ($member['performance_level'] === 'yellow' ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $member['avg_percentage'] }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium">{{ $member['avg_percentage'] }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                {{ $member['good_logs'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                                {{ $member['bad_logs'] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <button wire:click="closeCompareModal" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
