<div class="p-6 space-y-6">
    @if (session()->has('message'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-green-800 text-sm font-medium">{{ session('message') }}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Organization Chart</h1>
                    <p class="text-sm text-gray-600 mt-1">Interactive organizational hierarchy and staff structure</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                @if($viewType === 'hierarchy')
                <button onclick="document.getElementById('orgchart-container').style.zoom = '1'" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors font-medium text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                    <span>Reset View</span>
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <button wire:click="switchView('hierarchy')" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors font-medium text-sm {{ $viewType === 'hierarchy' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Hierarchy View</span>
                </button>
                <button wire:click="switchView('group')" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors font-medium text-sm {{ $viewType === 'group' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Group View</span>
                </button>
                <button wire:click="switchView('branch')" class="inline-flex items-center px-4 py-2 rounded-lg transition-colors font-medium text-sm {{ $viewType === 'branch' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Branch View</span>
                </button>
            </div>
            <div class="text-sm text-gray-600">
                @if($viewType === 'hierarchy')
                    <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg> Supervisor Chain</span>
                @elseif($viewType === 'group')
                    <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg> Team Groups</span>
                @else
                    <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg> Branch Structure</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <svg class="w-5 h-5 text-gray-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h3 class="text-base font-semibold text-gray-900">Search & Filter</h3>
        </div>
        <div class="flex items-center space-x-4">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="searchTerm"
                    placeholder="Search by name, email, position, or department..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
            </div>
            <select wire:model.live="selectedDepartment" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[200px]">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
            @if($searchTerm || $selectedDepartment)
            <button wire:click="$set('searchTerm', ''); $set('selectedDepartment', '')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                Clear
            </button>
            @endif
        </div>
        @if($searchTerm || $selectedDepartment)
        <div class="mt-3 text-sm text-gray-600">
            Showing {{ $organizationData->count() }} result(s)
        </div>
        @endif
    </div>

    @if($viewType === 'hierarchy' && $organizationData->isEmpty())
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No Results Found</h3>
        <p class="mt-1 text-gray-500">
            @if($searchTerm || $selectedDepartment)
                No employees match your search criteria. Try adjusting your filters.
            @else
                Add users with supervisor relationships to see the organization chart.
            @endif
        </p>
    </div>
    @elseif($viewType === 'hierarchy' && !$organizationData->isEmpty())
    <!-- Hierarchy View -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        @if($searchTerm || $selectedDepartment)
        <!-- Search Results View -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($organizationData as $user)
                <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-blue-400 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="relative">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-md">
                                <span class="text-white font-bold text-xl">
                                    {{ strtoupper(substr($user['name'], 0, 2)) }}
                                </span>
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 {{ $user['is_active'] ? 'bg-green-500' : 'bg-red-500' }} border-2 border-white rounded-full"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-900 text-lg mb-1">{{ $user['name'] }}</h4>
                            <p class="text-sm text-blue-600 font-medium mb-2">{{ $user['title'] }}</p>
                            <div class="space-y-1">
                                <div class="flex items-center text-xs text-gray-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $user['department'] }}
                                </div>
                                <div class="flex items-center text-xs text-gray-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $user['email'] }}
                                </div>
                                @if($user['supervisor_name'])
                                <div class="flex items-center text-xs text-gray-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Reports to: <span class="font-medium ml-1">{{ $user['supervisor_name'] }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <!-- Hierarchy Chart View -->
        <div class="relative">
            <!-- Zoom Controls -->
            <div class="absolute top-4 right-4 z-10 flex flex-col space-y-2">
                <button onclick="zoomIn()" class="p-2 bg-white rounded-lg shadow-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </button>
                <button onclick="zoomOut()" class="p-2 bg-white rounded-lg shadow-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </button>
                <button onclick="resetZoom()" class="p-2 bg-white rounded-lg shadow-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
                <button onclick="toggleOrientation()" class="p-2 bg-white rounded-lg shadow-lg hover:bg-gray-50 transition-colors" title="Toggle Horizontal/Vertical">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </button>
            </div>
            
            <div id="orgchart-container" class="min-h-[600px] bg-gray-50 rounded-lg overflow-hidden"></div>
        </div>
        @endif
    </div>
    @endif

    <!-- Group View -->
    @if($viewType === 'group')
    <div class="space-y-6">
        @if($groupData->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No Groups Found</h3>
            <p class="mt-1 text-gray-500">Create groups to organize your teams.</p>
        </div>
        @else
        @foreach($groupData as $group)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <!-- Group Header -->
            <div class="bg-indigo-50 border-b border-indigo-100 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $group['name'] }}</h3>
                            <div class="flex items-center text-sm text-gray-600 mt-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $group['branch_name'] }}
                            </div>
                            @if($group['description'])
                            <p class="text-sm text-gray-600 mt-1">{{ $group['description'] }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-900">{{ $group['member_count'] }}</div>
                        <div class="text-sm text-gray-600">Members</div>
                    </div>
                </div>
            </div>

            <!-- Group Stats -->
            <div class="bg-white px-6 py-4 border-b border-gray-200">
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($group['avg_performance'], 1) }}%</div>
                        <div class="text-xs text-gray-600">Avg Performance</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ $group['total_kpis'] }}</div>
                        <div class="text-xs text-gray-600">Total KPIs</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-xs text-gray-600">Created</div>
                        <div class="text-sm font-medium text-gray-900">{{ $group['created_at'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Group Leader -->
            <div class="px-6 py-4 bg-amber-50 border-b border-amber-100">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-600 font-medium uppercase">Group Leader</div>
                        <div class="text-base font-bold text-gray-900">{{ $group['leader']['name'] }}</div>
                        <div class="text-sm text-gray-600">{{ $group['leader']['position'] }}</div>
                    </div>
                    <button wire:click="showPerformanceModal({{ $group['leader']['id'] }})" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                        View Details
                    </button>
                </div>
            </div>

            <!-- Group Members -->
            <div class="p-6">
                <h4 class="text-sm font-semibold text-gray-700 uppercase mb-4">Team Members</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($group['members'] as $member)
                    <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors cursor-pointer border border-gray-100" wire:click="showPerformanceModal({{ $member['id'] }})">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <span class="text-indigo-600 font-bold text-sm">{{ strtoupper(substr($member['name'], 0, 2)) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-gray-900 truncate">{{ $member['name'] }}</div>
                                <div class="text-xs text-gray-600 truncate">{{ $member['position'] }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ $member['department'] }}</div>
                            </div>
                            @if($member['is_active'])
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            @else
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
    @endif

    <!-- Branch View -->
    @if($viewType === 'branch')
    <div class="space-y-8">
        @if($branchData->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No Branches Found</h3>
            <p class="mt-1 text-gray-500">Add branches to organize your organization.</p>
        </div>
        @else
        @foreach($branchData as $branch)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Branch Header -->
            <div class="bg-indigo-50 border-b border-indigo-100 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $branch['name'] }}</h2>
                            <div class="flex items-center text-sm text-gray-600 mt-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $branch['location'] }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-900">{{ $branch['group_count'] }}</div>
                        <div class="text-sm text-gray-600">Groups</div>
                        <div class="text-xl font-semibold text-gray-900 mt-2">{{ $branch['total_members'] }}</div>
                        <div class="text-xs text-gray-600">Total Members</div>
                    </div>
                </div>
            </div>

            <!-- Branch Groups -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Groups in this Branch</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($branch['groups'] as $group)
                    <div class="bg-white rounded-lg p-6 border border-gray-200 hover:border-indigo-300 hover:shadow-md transition-all">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">{{ $group['name'] }}</h4>
                                <p class="text-sm text-gray-600">{{ $group['member_count'] }} members</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($group['avg_performance'], 0) }}%</div>
                                <div class="text-xs text-gray-600">Performance</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 mb-3 p-3 bg-amber-50 rounded-lg border border-amber-100">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600">Leader</div>
                                <div class="font-semibold text-gray-900">{{ $group['leader']['name'] }}</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Total KPIs: <span class="font-bold text-gray-900">{{ $group['total_kpis'] }}</span></span>
                            <span class="text-gray-500 text-xs">{{ $group['created_at'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
    @endif

    @push('scripts')
    <!-- D3.js for advanced visualizations -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    
    <style>
        #orgchart-container {
            width: 100%;
            height: 600px;
            position: relative;
            cursor: grab;
        }
        
        #orgchart-container:active {
            cursor: grabbing;
        }
        
        .node-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            min-width: 220px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
        }
        
        .node-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateY(-4px) scale(1.02);
        }
        
        .node-card.active-user {
            border-color: #10b981;
        }
        
        .node-card.inactive-user {
            opacity: 0.6;
            border-color: #ef4444;
        }
        
        .node-card.collapsed {
            border-color: #f59e0b;
        }
        
        .node-avatar {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
            margin: 0 auto 12px;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
            transition: all 0.3s;
        }
        
        .node-card:hover .node-avatar {
            transform: scale(1.1);
            box-shadow: 0 8px 12px -2px rgba(59, 130, 246, 0.4);
        }
        
        .node-name {
            font-weight: 600;
            font-size: 15px;
            color: #111827;
            text-align: center;
            margin-bottom: 4px;
        }
        
        .node-title {
            font-size: 13px;
            color: #6b7280;
            text-align: center;
            margin-bottom: 8px;
        }
        
        .node-department {
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
            padding: 4px 8px;
            background: #f3f4f6;
            border-radius: 6px;
            display: inline-block;
        }
        
        .node-actions {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .node-card:hover .node-actions {
            opacity: 1;
        }
        
        .node-action-btn {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .node-action-btn:hover {
            background: #3b82f6;
            color: white;
        }
        
        .expand-btn {
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            z-index: 10;
        }
        
        .expand-btn:hover {
            background: #2563eb;
            transform: translateX(-50%) scale(1.2);
        }
        
        .link {
            fill: none;
            stroke: #cbd5e1;
            stroke-width: 2px;
            transition: all 0.3s;
        }
        
        .link:hover {
            stroke: #3b82f6;
            stroke-width: 3px;
        }
        
        .subordinate-count {
            position: absolute;
            bottom: -8px;
            right: -8px;
            background: #3b82f6;
            color: white;
            font-size: 11px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .node-card {
            animation: fadeIn 0.3s ease-out;
        }
        
        .performance-badge {
            position: absolute;
            top: -8px;
            left: -8px;
            color: white;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 4px;
            z-index: 10;
        }
        
        .performance-badge svg {
            width: 12px;
            height: 12px;
        }
    </style>
    
    <script>
        // Modern D3.js Organization Chart
        let currentZoom = 1;
        let currentOrientation = 'vertical'; // 'vertical' or 'horizontal'
        let collapsedNodes = new Set();
        
        const orgData = @json($organizationData);
        console.log('Organization Data:', orgData);
        
        if (!orgData || orgData.length === 0) {
            document.getElementById('orgchart-container').innerHTML = 
                '<div class="text-center py-12 text-gray-500">No organization data available. Please add users with supervisor relationships.</div>';
        } else {
            initializeOrgChart(orgData[0]);
        }
        
        function initializeOrgChart(data) {
            const container = d3.select('#orgchart-container');
            container.selectAll('*').remove();
            
            const width = container.node().clientWidth;
            const height = 600;
            
            const svg = container.append('svg')
                .attr('width', width)
                .attr('height', height);
            
            const g = svg.append('g')
                .attr('transform', `translate(${width/2},50)`);
            
            // Add zoom behavior
            const zoom = d3.zoom()
                .scaleExtent([0.3, 3])
                .on('zoom', (event) => {
                    g.attr('transform', event.transform);
                    currentZoom = event.transform.k;
                });
            
            svg.call(zoom);
            
            // Create tree layout
            const treeLayout = d3.tree()
                .nodeSize([280, 180])
                .separation((a, b) => (a.parent === b.parent ? 1 : 1.2));
            
            // Convert data to hierarchy
            const root = d3.hierarchy(data, d => {
                if (collapsedNodes.has(d.id)) return null;
                return d.subordinates && d.subordinates.length > 0 ? d.subordinates : null;
            });
            
            treeLayout(root);
            
            // Draw links
            const links = g.selectAll('.link')
                .data(root.links())
                .enter()
                .append('path')
                .attr('class', 'link')
                .attr('d', d3.linkVertical()
                    .x(d => d.x)
                    .y(d => d.y));
            
            // Draw nodes
            const nodes = g.selectAll('.node')
                .data(root.descendants())
                .enter()
                .append('g')
                .attr('class', 'node')
                .attr('transform', d => `translate(${d.x},${d.y})`);
            
            // Add node cards
            nodes.append('foreignObject')
                .attr('width', 240)
                .attr('height', 160)
                .attr('x', -120)
                .attr('y', -80)
                .append('xhtml:div')
                .html(d => createNodeCard(d.data));
            
            // Center the chart
            const bounds = g.node().getBBox();
            const fullWidth = bounds.width;
            const fullHeight = bounds.height;
            const midX = bounds.x + fullWidth / 2;
            const midY = bounds.y + fullHeight / 2;
            
            svg.call(zoom.transform, d3.zoomIdentity
                .translate(width / 2, height / 2)
                .scale(0.8)
                .translate(-midX, -midY));
        }
        
        function createNodeCard(data) {
            const hasSubordinates = data.subordinates && data.subordinates.length > 0;
            const isCollapsed = collapsedNodes.has(data.id);
            const statusClass = data.is_active ? 'active-user' : 'inactive-user';
            const collapsedClass = isCollapsed ? 'collapsed' : '';
            
            // Performance score (will be passed from backend)
            const performanceScore = data.performance_score || 0;
            const performanceColor = getPerformanceColor(performanceScore);
            
            return `
                <div class="node-card ${statusClass} ${collapsedClass}" onclick="showPerformanceDetail(${data.id})">
                    <div class="node-actions" onclick="event.stopPropagation()">
                        <div class="node-action-btn" onclick="editNode(${data.id})" title="Edit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div class="node-action-btn" onclick="addSubordinate(${data.id})" title="Add Subordinate">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Performance Badge -->
                    <div class="performance-badge" style="background: ${performanceColor}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        ${performanceScore.toFixed(0)}
                    </div>
                    
                    <div class="node-avatar">
                        ${data.name.substring(0, 2).toUpperCase()}
                    </div>
                    
                    <div class="node-name">${data.name}</div>
                    <div class="node-title">${data.title}</div>
                    <div class="node-department">${data.department || 'N/A'}</div>
                    
                    ${hasSubordinates ? `
                        <div class="expand-btn" onclick="event.stopPropagation(); toggleNode(${data.id})">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${isCollapsed ? 'M12 4v16m8-8H4' : 'M20 12H4'}"/>
                            </svg>
                        </div>
                        ${!isCollapsed ? `<div class="subordinate-count">${data.subordinates.length}</div>` : ''}
                    ` : ''}
                </div>
            `;
        }
        
        function getPerformanceColor(score) {
            if (score >= 90) return '#10b981'; // Green
            if (score >= 80) return '#3b82f6'; // Blue
            if (score >= 70) return '#f59e0b'; // Yellow
            if (score >= 60) return '#f97316'; // Orange
            return '#ef4444'; // Red
        }
        
        function showPerformanceDetail(userId) {
            @this.call('showPerformanceModal', userId);
        }
        
        function toggleNode(nodeId) {
            if (collapsedNodes.has(nodeId)) {
                collapsedNodes.delete(nodeId);
            } else {
                collapsedNodes.add(nodeId);
            }
            initializeOrgChart(orgData[0]);
        }
        
        function zoomIn() {
            const svg = d3.select('#orgchart-container svg');
            const zoom = d3.zoom().scaleExtent([0.3, 3]);
            svg.transition().call(zoom.scaleBy, 1.3);
        }
        
        function zoomOut() {
            const svg = d3.select('#orgchart-container svg');
            const zoom = d3.zoom().scaleExtent([0.3, 3]);
            svg.transition().call(zoom.scaleBy, 0.7);
        }
        
        function resetZoom() {
            collapsedNodes.clear();
            initializeOrgChart(orgData[0]);
        }
        
        function toggleOrientation() {
            currentOrientation = currentOrientation === 'vertical' ? 'horizontal' : 'vertical';
            initializeOrgChart(orgData[0]);
        }
        
        function editNode(nodeId) {
            Livewire.dispatch('openEditModal', { userId: nodeId });
            @this.call('openEditModal', nodeId);
        }
        
        function addSubordinate(nodeId) {
            @this.call('openAddModal', nodeId);
        }
        
        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            Livewire.on('userUpdated', () => {
                setTimeout(() => {
                    location.reload();
                }, 1000);
            });
            
            Livewire.on('userAdded', () => {
                setTimeout(() => {
                    location.reload();
                }, 1000);
            });
        });
    </script>
    @endpush

    <!-- Edit User Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeEditModal">
        <div class="relative top-20 mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-lg bg-white" wire:click.stop>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Edit User</h3>
                <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="updateUser">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" wire:model="editName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter name">
                        @error('editName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" wire:model="editEmail" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter email">
                        @error('editEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                        <select wire:model="editDepartmentId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('editDepartmentId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Position *</label>
                        <select wire:model="editPositionId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Position</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                            @endforeach
                        </select>
                        @error('editPositionId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Supervisor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supervisor</label>
                        <select wire:model="editSupervisorId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">No Supervisor (Top Level)</option>
                            @foreach(\App\Models\User::where('id', '!=', $editUserId)->active()->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->position->name ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                        @error('editSupervisorId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex items-center space-x-4 mt-2">
                            <label class="flex items-center">
                                <input type="radio" wire:model="editIsActive" value="1" class="mr-2">
                                <span class="text-sm">Active</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" wire:model="editIsActive" value="0" class="mr-2">
                                <span class="text-sm">Inactive</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-8">
                    <button type="button" wire:click="closeEditModal" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Add Subordinate Modal -->
    @if($showAddModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeAddModal">
        <div class="relative top-20 mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-lg bg-white" wire:click.stop>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Add Subordinate</h3>
                <button wire:click="closeAddModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="addSubordinate">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" wire:model="addName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter name">
                        @error('addName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" wire:model="addEmail" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter email">
                        @error('addEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                        <input type="password" wire:model="addPassword" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter password (min 8 characters)">
                        @error('addPassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                        <select wire:model="addDepartmentId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('addDepartmentId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Position *</label>
                        <select wire:model="addPositionId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Position</option>
                            @foreach($positions as $pos)
                                <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                            @endforeach
                        </select>
                        @error('addPositionId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> This user will be added as a subordinate to the selected supervisor. The role will be automatically assigned based on the position.
                    </p>
                </div>

                <div class="flex justify-end space-x-3 mt-8">
                    <button type="button" wire:click="closeAddModal" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Add Subordinate
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Performance Detail Modal -->
    @if($showPerformanceModal && $performanceUser)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closePerformanceModal">
        <div class="relative top-10 mx-auto p-6 border w-full max-w-6xl shadow-lg rounded-lg bg-white" wire:click.stop>
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($performanceUser->name, 0, 2) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $performanceUser->name }}</h3>
                        <p class="text-gray-600">{{ $performanceUser->position->name ?? 'N/A' }} • {{ $performanceUser->department->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <button wire:click="closePerformanceModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Overall Performance Score -->
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                        <div class="text-center">
                            <p class="text-sm opacity-90 mb-2">Overall Performance</p>
                            <div class="text-6xl font-bold mb-2">{{ number_format($performanceData->overall_score ?? 0, 0) }}</div>
                            <p class="text-lg font-semibold">{{ $performanceData->rating ?? 'N/A' }}</p>
                        </div>
                        <div class="mt-6 pt-6 border-t border-blue-400">
                            <div class="flex justify-between text-sm mb-2">
                                <span>This Month</span>
                                <span class="font-semibold">{{ date('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Performance Breakdown</h4>
                        
                        <div class="space-y-4">
                            <!-- KPI Score -->
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">KPI Completion</span>
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($performanceData->kpi_score ?? 0, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ min($performanceData->kpi_score ?? 0, 100) }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $performanceData->kpis_completed ?? 0 }} / {{ $performanceData->kpis_total ?? 0 }} KPIs completed</p>
                            </div>

                            <!-- Task Score -->
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Task Performance (Good Logs)</span>
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($performanceData->task_score ?? 0, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full transition-all" style="width: {{ min($performanceData->task_score ?? 0, 100) }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $performanceData->tasks_completed ?? 0 }} good / {{ $performanceData->tasks_total ?? 0 }} total logs</p>
                            </div>

                            <!-- Quality Score -->
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Quality Score (Avg %)</span>
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($performanceData->quality_score ?? 0, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full transition-all" style="width: {{ min($performanceData->quality_score ?? 0, 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Attendance Score -->
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Attendance</span>
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($performanceData->attendance_score ?? 0, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-600 h-2 rounded-full transition-all" style="width: {{ min($performanceData->attendance_score ?? 0, 100) }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Default value (can be integrated with attendance system)</p>
                            </div>

                            <!-- Collaboration Score -->
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Collaboration</span>
                                    <span class="text-sm font-bold text-gray-900">{{ number_format($performanceData->collaboration_score ?? 0, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-pink-600 h-2 rounded-full transition-all" style="width: {{ min($performanceData->collaboration_score ?? 0, 100) }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Default value (can be integrated with team activities)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Performance -->
            @if($teamPerformanceData && $teamPerformanceData['team_size'] > 1)
            <div class="mt-6">
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Team Performance</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Team Size</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $teamPerformanceData['team_size'] }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Average Score</p>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($teamPerformanceData['average_score'], 1) }}</p>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Total KPIs</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $teamPerformanceData['total_kpis_completed'] }}</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Total Tasks</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ $teamPerformanceData['total_tasks_completed'] }}</p>
                        </div>
                    </div>

                    <!-- Top Performers -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($teamPerformanceData['top_performer'])
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-xl">
                                    🏆
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Top Performer</p>
                                    @php
                                        $topUser = \App\Models\User::find($teamPerformanceData['top_performer']->user_id);
                                    @endphp
                                    <p class="font-semibold text-gray-900">{{ $topUser->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-green-600 font-bold">{{ number_format($teamPerformanceData['top_performer']->overall_score ?? 0, 1) }} points</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($teamPerformanceData['lowest_performer'])
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-xl">
                                    📈
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Needs Support</p>
                                    @php
                                        $lowUser = \App\Models\User::find($teamPerformanceData['lowest_performer']->user_id);
                                    @endphp
                                    <p class="font-semibold text-gray-900">{{ $lowUser->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-orange-600 font-bold">{{ number_format($teamPerformanceData['lowest_performer']->overall_score ?? 0, 1) }} points</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="flex justify-end mt-6">
                <button wire:click="closePerformanceModal" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif
</div>