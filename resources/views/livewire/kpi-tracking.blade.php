<div>
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

    {{-- Error Message --}}
    @if (session()->has('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-red-800 text-sm font-medium">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">KPI Performance Tracking</h1>
                    <p class="text-sm text-gray-600 mt-1">Systematic performance measurement and tracking</p>
                </div>
            </div>

            {{-- Selection Controls --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="flex-1 relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Ranking Code or User</label>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                           placeholder="Type to search..."
                           wire:click.away="hideSuggestions">
                    
                    {{-- Autocomplete Suggestions --}}
                    @if($showSuggestions && !empty($searchSuggestions))
                    <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
                        @foreach($searchSuggestions as $index => $suggestion)
                        <div wire:click="selectSuggestion('{{ $suggestion['text'] }}')" 
                             class="px-4 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0 {{ $selectedSuggestionIndex === $index ? 'bg-indigo-50' : '' }}">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-900">{{ $suggestion['text'] }}</span>
                                <span class="text-xs text-gray-500 px-2 py-1 bg-gray-100 rounded">
                                    @if($suggestion['type'] === 'ranking_code')
                                        Code: {{ $suggestion['id'] }}
                                    @else
                                        User
                                    @endif
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Ranking Code</label>
                    <select wire:model.live="selectedRankingCode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Choose Ranking Code</option>
                        @foreach($filteredRankingCodes as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Date</label>
                    <input type="date" wire:model.live="selectedDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    @if($selectedRankingCode && $selectedDate)
    <div class="space-y-6">
        {{-- KPI Assessment Cards --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Personality KPI --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Personality</h3>
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">{{ $personalityScore }}</span>
                </div>
                <div class="space-y-3">
                    @foreach($personalityKpis as $key => $kpi)
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="personalityKpis.{{ $key }}.checked" wire:change="updateScores"
                               class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 h-4 w-4">
                        <span class="text-sm text-gray-700">{{ $kpi['label'] }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Team Management KPI --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Team Management</h3>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">{{ $performanceScore }}</span>
                </div>
                
                {{-- Group KPIs by category --}}
                @php
                    $groupedKpis = [];
                    foreach($teamManagementKpis as $key => $kpi) {
                        $category = $kpi['category'] ?? 'General';
                        if (!isset($groupedKpis[$category])) {
                            $groupedKpis[$category] = [];
                        }
                        $groupedKpis[$category][$key] = $kpi;
                    }
                @endphp
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($groupedKpis as $category => $kpis)
                    <div class="bg-gray-50 rounded-lg p-3">
                        <h4 class="text-sm font-semibold text-gray-800 mb-2 border-b border-gray-200 pb-1">{{ $category }}</h4>
                        <div class="space-y-2">
                            @foreach($kpis as $key => $kpi)
                            <label class="flex items-start space-x-2 cursor-pointer text-xs">
                                <input type="checkbox" wire:model.live="teamManagementKpis.{{ $key }}.checked" wire:change="updateScores"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-3 w-3 mt-0.5">
                                <span class="text-gray-700 leading-tight">{{ $kpi['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Additional Metrics --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Customer Follow-Up --}}
            <div class="bg-gray-50 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Follow-Up</h3>
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">{{ $customerFollowUpScore }}/10</span>
                </div>
                <div class="space-y-4">
                    {{-- Customer Follow-Up KPI --}}
                    @foreach($customerFollowUpKpis as $key => $kpi)
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="customerFollowUpKpis.{{ $key }}.checked"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                        <span class="text-sm text-gray-700">{{ $kpi['label'] }}</span>
                    </label>
                    @endforeach
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of People</label>
                        <input type="number" wire:model.live="numberOfPeople" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Score (0-10)</label>
                        <input type="number" wire:model.live="customerFollowUpScore" min="0" max="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>
            </div>

            {{-- Supervised Level --}}
            <div class="bg-gray-50 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Supervised Level</h3>
                    <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">{{ $supervisedLevelScore }}/5</span>
                </div>
                <div class="space-y-4">
                    {{-- Supervised Level KPI --}}
                    @foreach($supervisedLevelKpis as $key => $kpi)
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="supervisedLevelKpis.{{ $key }}.checked"
                               class="rounded border-gray-300 text-orange-600 focus:ring-orange-500 h-4 w-4">
                        <span class="text-sm text-gray-700">{{ $kpi['label'] }}</span>
                    </label>
                    @endforeach
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supervisor Score (0-5)</label>
                        <input type="number" wire:model.live="supervisedLevelScore" min="0" max="5"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea wire:model="kpiNotes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm"
                                  placeholder="Enter any additional notes..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Photo Upload</label>
                        <input type="file" wire:model="photo" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm">
                        @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Upload a photo related to this KPI measurement (max 2MB)</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
            <button wire:click="resetForm" wire:loading.attr="disabled"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">
                <span wire:loading.remove>Reset Form</span>
                <span wire:loading>
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Resetting...
                </span>
            </button>
            <button wire:click="submitForm" wire:loading.attr="disabled"
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                <span wire:loading.remove>Save KPI Data</span>
                <span wire:loading>
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>
        </div>
    </div>
    @else
    {{-- Selection Prompt --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12">
        <div class="text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Select Ranking Code and Date</h3>
            <p class="text-gray-500 mb-6">Choose a ranking code and date to start tracking KPI performance metrics.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ranking Code</label>
                    <select wire:model.live="selectedRankingCode" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Ranking Code</option>
                        @foreach($filteredRankingCodes as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" wire:model.live="selectedDate" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
