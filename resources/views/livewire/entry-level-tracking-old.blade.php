<div class="p-6">
    <!-- Header with Tabs -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">KPI Tracking</h1>
        <p class="mt-2 text-sm text-gray-600">Real-time detailed performance tracking</p>
        
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

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="mb-6 flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0 gap-4">
        <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
            <!-- Search -->
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Search by employee name..."
                class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
            
            <!-- Filter by Status -->
            <select 
                wire:model.live="filterStatus"
                class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
                <option value="">All Status</option>
                <option value="compliant">Compliant</option>
                <option value="violation">Violation</option>
            </select>

            <!-- Filter by Period -->
            <select 
                wire:model.live="filterPeriod"
                class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
                <option value="all">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
            </select>
        </div>

        <button 
            wire:click="openCreateModal"
            class="flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>New Evaluation</span>
        </button>
    </div>

    <!-- Evaluations Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Scores</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evaluator</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($checklists as $checklist)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold text-blue-700">{{ substr($checklist->user->name, 0, 2) }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $checklist->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $checklist->user->position?->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $checklist->evaluation_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                <span class="px-2 py-1 text-xs rounded {{ $checklist->personality_score ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    P: {{ $checklist->personality_score ? '✓' : '✗' }}
                                </span>
                                <span class="px-2 py-1 text-xs rounded {{ $checklist->performance_score ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    Perf: {{ $checklist->performance_score ? '✓' : '✗' }}
                                </span>
                                <span class="px-2 py-1 text-xs rounded {{ $checklist->hospitality_score ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    H: {{ $checklist->hospitality_score ? '✓' : '✗' }}
                                </span>
                                <span class="px-2 py-1 text-xs rounded {{ $checklist->cleaning_score ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    C: {{ $checklist->cleaning_score ? '✓' : '✗' }}
                                </span>
                                <span class="px-2 py-1 text-xs rounded {{ $checklist->learning_achievement_score ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    L: {{ $checklist->learning_achievement_score ? '✓' : '✗' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-2xl font-bold {{ $checklist->score_color === 'green' ? 'text-green-600' : ($checklist->score_color === 'yellow' ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($checklist->total_score, 1) }}%
                                </span>
                                <span class="ml-2 text-xs text-gray-500">{{ $checklist->score_label }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $checklist->status === 'compliant' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($checklist->status) }}
                            </span>
                            @if($checklist->status === 'violation' && $checklist->impacts->count() > 0)
                                <span class="ml-1 text-xs text-red-600" title="Impacts {{ $checklist->impacts->count() }} supervisors">
                                    ⚠️ {{ $checklist->impacts->count() }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $checklist->evaluator->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button 
                                wire:click="openEditModal({{ $checklist->id }})"
                                class="text-blue-600 hover:text-blue-900 mr-3"
                            >
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button 
                                wire:click="delete({{ $checklist->id }})"
                                wire:confirm="Are you sure?"
                                class="text-red-600 hover:text-red-900"
                            >
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-2">No evaluations found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $checklists->links() }}
        </div>
    </div>

    <!-- Evaluation Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4 sticky top-0 bg-white pb-4 border-b">
                <h3 class="text-xl font-bold text-gray-900">
                    {{ $isEditing ? 'Edit Evaluation' : 'New Entry Level Evaluation' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="save">
                <div class="space-y-6">
                    <!-- Employee and Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Employee *</label>
                            <select 
                                wire:model="user_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Select Employee</option>
                                @foreach($entryLevelUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->position?->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Evaluation Date *</label>
                            <input 
                                type="date" 
                                wire:model="evaluation_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            >
                            @error('evaluation_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- 5 Key Areas with Good/Bad Checkboxes -->
                    @php
                        $areas = [
                            ['key' => 'personality', 'label' => 'Personality (စရိုက်လက္ခဏာ)', 'icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['key' => 'performance', 'label' => 'Performance (စွမ်းဆောင်ရည်)', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                            ['key' => 'hospitality', 'label' => 'Hospitality (ဧည့်ဝန်ကျေပွန်မှု)', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                            ['key' => 'cleaning', 'label' => 'Cleaning (သန့်ရှင်းမှု)', 'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
                            ['key' => 'learning_achievement', 'label' => 'Learning Achievement (သင်ယူမှု)', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ];
                    @endphp

                    @foreach($areas as $area)
                    <div class="bg-white rounded-xl shadow-lg p-6 border-2 {{ $this->{$area['key'].'_score'} ? 'border-green-400' : 'border-gray-200' }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $area['icon'] }}"/>
                                </svg>
                                <h3 class="text-lg font-bold text-gray-900">{{ $area['label'] }}</h3>
                            </div>
                            @if($this->{$area['key'].'_score'})
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">GOOD</span>
                            @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">BAD</span>
                            @endif
                        </div>

                        <!-- Good/Bad Toggle -->
                        <div class="flex items-center space-x-4 mb-4">
                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    wire:model="{{ $area['key'] }}_score"
                                    class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                >
                                <span class="ml-2 text-sm font-medium text-gray-700">Mark as Good</span>
                            </label>
                        </div>

                        <!-- Notes -->
                        <textarea 
                            wire:model="{{ $area['key'] }}_notes"
                            rows="2"
                            placeholder="Add notes or comments..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                        ></textarea>
                        @error($area['key'].'_score') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    @endforeach

                    <!-- General Comments -->
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-2">General Comments</label>
                        <textarea 
                            wire:model="general_comments"
                            rows="3"
                            placeholder="Overall evaluation comments..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        ></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3 sticky bottom-0 bg-white pt-4 border-t">
                    <button 
                        type="button"
                        wire:click="closeModal"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                    >
                        {{ $isEditing ? 'Update Evaluation' : 'Save Evaluation' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
