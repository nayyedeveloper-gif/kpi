<div>
    <!-- Page Header with Tabs -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">KPI Tracking</h1>
                <p class="text-gray-600 mt-1">Real-time detailed performance tracking</p>
            </div>
            <button wire:click="viewDetails" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>View Timeline</span>
            </button>
        </div>
        
        <!-- Tabs -->
        <div class="mt-4 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('kpi.index') }}" 
                   class="{{ request()->routeIs('kpi.index') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Operation Level
                </a>
                <a href="{{ route('kpi.entry-level') }}" 
                   class="{{ request()->routeIs('kpi.entry-level') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Entry Level
                </a>
            </nav>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Date & User Selection -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" wire:model.live="selectedDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <select wire:model.live="selectedUser" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->position->name ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Entry Level Cards -->
    @if($checklist)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Personality -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-2 {{ $checklist->personality_score ? 'border-green-400' : 'border-gray-200' }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Personality</h3>
                @if($checklist->personality_score)
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">GOOD</span>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">NOT SET</span>
                @endif
            </div>
            <div class="flex space-x-2">
                <button wire:click="openLogModal('personality_score')" class="flex-1 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center space-x-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span class="font-semibold">Good</span>
                </button>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                Logs: 11
            </div>
        </div>

        <!-- Performance -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-2 {{ $checklist->performance_score ? 'border-green-400' : 'border-gray-200' }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Performance</h3>
                @if($checklist->performance_score)
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">GOOD</span>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">NOT SET</span>
                @endif
            </div>
            <div class="flex space-x-2">
                <button wire:click="openLogModal('performance_score')" class="flex-1 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center space-x-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span class="font-semibold">Good</span>
                </button>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                Logs: 11
            </div>
        </div>

        <!-- Hospitality -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-2 {{ $checklist->hospitality_score ? 'border-green-400' : 'border-gray-200' }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Hospitality</h3>
                @if($checklist->hospitality_score)
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">GOOD</span>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">NOT SET</span>
                @endif
            </div>
            <div class="flex space-x-2">
                <button wire:click="openLogModal('hospitality_score')" class="flex-1 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center space-x-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span class="font-semibold">Good</span>
                </button>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                Logs: 11
            </div>
        </div>

        <!-- Cleaning -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-2 {{ $checklist->cleaning_score ? 'border-green-400' : 'border-gray-200' }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Cleaning</h3>
                @if($checklist->cleaning_score)
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">GOOD</span>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">NOT SET</span>
                @endif
            </div>
            <div class="flex space-x-2">
                <button wire:click="openLogModal('cleaning_score')" class="flex-1 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center space-x-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span class="font-semibold">Good</span>
                </button>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                Logs: 11
            </div>
        </div>

        <!-- Learning Achievement -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-2 {{ $checklist->learning_achievement_score ? 'border-green-400' : 'border-gray-200' }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Learning Achievement</h3>
                @if($checklist->learning_achievement_score)
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">GOOD</span>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">NOT SET</span>
                @endif
            </div>
            <div class="flex space-x-2">
                <button wire:click="openLogModal('learning_achievement_score')" class="flex-1 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center space-x-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span class="font-semibold">Good</span>
                </button>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                Logs: 11
            </div>
        </div>
    </div>
    @endif

    <!-- Log Modal -->
    @if($showLogModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add Log Entry</h3>
                <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="saveLog">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex space-x-4">
                            <button type="button" wire:click="$set('logStatus', 'good')" class="flex-1 py-3 rounded-lg border-2 transition-all {{ $logStatus === 'good' ? 'bg-green-500 border-green-600 text-white' : 'bg-white border-gray-300 text-gray-700 hover:border-green-400' }}">
                                <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                </svg>
                                Good
                            </button>
                            <button type="button" wire:click="$set('logStatus', 'bad')" class="flex-1 py-3 rounded-lg border-2 transition-all {{ $logStatus === 'bad' ? 'bg-red-500 border-red-600 text-white' : 'bg-white border-gray-300 text-gray-700 hover:border-red-400' }}">
                                <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/>
                                </svg>
                                Bad
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea wire:model="logNotes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Add remarks or comments..."></textarea>
                        @error('logNotes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Photo Evidence (Optional)</label>
                        <input type="file" wire:model="logPhoto" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @error('logPhoto') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @if ($logPhoto)
                            <img src="{{ $logPhoto->temporaryUrl() }}" class="mt-2 h-32 rounded-lg">
                        @endif
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" wire:click="closeModals" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Save Log
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Details Modal -->
    @if($showDetailsModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Daily Timeline - {{ \Carbon\Carbon::parse($viewDate)->format('F d, Y') }}</h3>
                <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($dailyLogs as $log)
                <div class="bg-gray-50 rounded-lg p-4 border-l-4 {{ $log->status === 'good' ? 'border-green-500' : 'border-red-500' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                @if($log->status === 'good')
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">GOOD</span>
                                @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">BAD</span>
                                @endif
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">{{ $log->area_name }}</span>
                                <span class="text-sm text-gray-600">{{ $log->logged_at->format('h:i A') }}</span>
                            </div>
                            @if($log->notes)
                            <p class="text-gray-700 text-sm mb-2">{{ $log->notes }}</p>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">
                            by {{ $log->user->name }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    No logs for this date yet.
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>
