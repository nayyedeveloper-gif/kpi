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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">KPI Tracking</h1>
                    <p class="text-sm text-gray-600 mt-1">Real-time detailed performance tracking</p>
                </div>
            </div>
            <button wire:click="viewDetails" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>View Timeline</span>
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex space-x-2">
            <a href="{{ route('kpi.index') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg transition-colors font-medium text-sm {{ request()->routeIs('kpi.index') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Operation Level
            </a>
            <a href="{{ route('kpi.entry-level') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg transition-colors font-medium text-sm {{ request()->routeIs('kpi.entry-level') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Entry Level
            </a>
        </div>
    </div>

    <!-- Date & User Selection -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <svg class="w-5 h-5 text-gray-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-base font-semibold text-gray-900">Select Date & User</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" wire:model.live="selectedDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <select wire:model.live="selectedUser" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->position->name ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    @if($measurement)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Ready to Sale -->
        <div class="bg-white rounded-xl shadow-sm border-2 {{ $measurement->ready_to_sale ? 'border-green-400' : 'border-gray-200' }} p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Ready to Sale</h3>
                @if($measurement->ready_to_sale)
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">GOOD</span>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">NOT SET</span>
                @endif
            </div>
            <div class="flex space-x-2">
                <button wire:click="openLogModal('ready_to_sale')" class="flex-1 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center space-x-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span class="font-semibold">Good</span>
                </button>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                Logs: {{ $measurement->logs()->where('kpi_measurement_id', $measurement->id)->count() }}
            </div>
        </div>

        <!-- Counter Check -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-2 {{ $measurement->counter_check ? 'border-green-400' : 'border-gray-200' }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Counter Check</h3>
                @if($measurement->counter_check)
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">GOOD</span>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">NOT SET</span>
                @endif
            </div>
            <div class="flex space-x-2">
                <button wire:click="openLogModal('counter_check')" class="flex-1 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center space-x-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span class="font-semibold">Good</span>
                </button>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                Logs: {{ $measurement->logs()->where('kpi_measurement_id', $measurement->id)->count() }}
            </div>
        </div>

        <!-- Cleanliness -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-2 {{ $measurement->cleanliness ? 'border-green-400' : 'border-gray-200' }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Cleanliness</h3>
                @if($measurement->cleanliness)
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">GOOD</span>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">NOT SET</span>
                @endif
            </div>
            <div class="flex space-x-2">
                <button wire:click="openLogModal('cleanliness')" class="flex-1 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center space-x-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span class="font-semibold">Good</span>
                </button>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                Logs: {{ $measurement->logs()->where('kpi_measurement_id', $measurement->id)->count() }}
            </div>
        </div>

        <!-- Stock Check -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-2 {{ $measurement->stock_check ? 'border-green-400' : 'border-gray-200' }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Stock Check</h3>
                @if($measurement->stock_check)
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">GOOD</span>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">NOT SET</span>
                @endif
            </div>
            <div class="flex space-x-2">
                <button wire:click="openLogModal('stock_check')" class="flex-1 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center space-x-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span class="font-semibold">Good</span>
                </button>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                Logs: {{ $measurement->logs()->where('kpi_measurement_id', $measurement->id)->count() }}
            </div>
        </div>

        <!-- Order Handling -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-2 {{ $measurement->order_handling ? 'border-green-400' : 'border-gray-200' }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Order Handling</h3>
                @if($measurement->order_handling)
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">GOOD</span>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">NOT SET</span>
                @endif
            </div>
            <div class="flex space-x-2">
                <button wire:click="openLogModal('order_handling')" class="flex-1 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center space-x-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span class="font-semibold">Good</span>
                </button>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                Logs: {{ $measurement->logs()->where('kpi_measurement_id', $measurement->id)->count() }}
            </div>
        </div>

        <!-- Customer Followup -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-2 {{ $measurement->customer_followup ? 'border-green-400' : 'border-gray-200' }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Customer Followup</h3>
                @if($measurement->customer_followup)
                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">GOOD</span>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">NOT SET</span>
                @endif
            </div>
            <div class="flex space-x-2">
                <button wire:click="openLogModal('customer_followup')" class="flex-1 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-center space-x-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span class="font-semibold">Good</span>
                </button>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                Logs: {{ $measurement->logs()->where('kpi_measurement_id', $measurement->id)->count() }}
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
                                @if($log->kpi_field)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">{{ $log->kpi_field_name }}</span>
                                @endif
                                <span class="text-sm text-gray-600">{{ $log->logged_at->format('h:i A') }}</span>
                            </div>
                            @if($log->notes)
                            <p class="text-gray-700 text-sm mb-2">{{ $log->notes }}</p>
                            @endif
                            @if($log->photo_path)
                            <img src="{{ Storage::url($log->photo_path) }}" class="h-32 rounded-lg mt-2">
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
