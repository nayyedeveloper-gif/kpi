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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">KPI Master Setup</h1>
                    <p class="text-sm text-gray-600 mt-1">Configure cascading KPI accountability system</p>
                </div>
            </div>
            <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>New Configuration</span>
            </button>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-indigo-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-sm font-semibold text-indigo-900 mb-1">Cascading Accountability System</h3>
                <p class="text-sm text-indigo-800">
                    Configure how KPI performance impacts cascade through the organizational hierarchy. 
                    When a staff member receives good/bad performance, their supervisors are also affected based on configured weights.
                </p>
            </div>
        </div>
    </div>

    <!-- Configurations List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($configurations->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No Configurations Yet</h3>
            <p class="mt-1 text-gray-500">Get started by creating your first KPI configuration.</p>
            <button wire:click="openCreateModal" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                Create Configuration
            </button>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Configuration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Checker Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cascade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($configurations as $config)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $config->name }}</div>
                            @if($config->description)
                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($config->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $config->level_type === 'operation' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ ucfirst($config->level_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $config->checkerRole->display_name ?? $config->checkerRole->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $config->targetRole->display_name ?? $config->targetRole->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($config->cascade_enabled)
                            <span class="flex items-center text-xs text-green-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $config->max_cascade_levels }} Levels
                            </span>
                            @else
                            <span class="text-xs text-gray-400">Disabled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="toggleActive({{ $config->id }})" class="flex items-center">
                                @if($config->is_active)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <button wire:click="openEditModal({{ $config->id }})" class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $config->id }})" onclick="return confirm('Are you sure you want to delete this configuration?')" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Configuration Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
        <div class="relative top-10 mx-auto p-6 border w-full max-w-4xl shadow-lg rounded-lg bg-white mb-10" wire:click.stop>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">
                    {{ $editMode ? 'Edit Configuration' : 'New Configuration' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="save">
                <!-- Basic Information -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Configuration Name *</label>
                            <input type="text" wire:model="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Operation Level Sales Tracking">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Level Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Level Type *</label>
                            <select wire:model="level_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="operation">Operation Level</option>
                                <option value="entry">Entry Level</option>
                            </select>
                            @error('level_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Max Cascade Levels -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Cascade Levels *</label>
                            <input type="number" wire:model="max_cascade_levels" min="1" max="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('max_cascade_levels') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Checker Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Checker Role * <span class="text-xs text-gray-500">(Who can check)</span></label>
                            <select wire:model="checker_role_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name ?? $role->name }}</option>
                                @endforeach
                            </select>
                            @error('checker_role_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Target Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Target Role * <span class="text-xs text-gray-500">(Who gets checked)</span></label>
                            <select wire:model="target_role_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name ?? $role->name }}</option>
                                @endforeach
                            </select>
                            @error('target_role_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea wire:model="description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Optional description..."></textarea>
                        </div>

                        <!-- Toggles -->
                        <div class="md:col-span-2 flex items-center space-x-6">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="cascade_enabled" class="mr-2 w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Enable Cascading</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="is_active" class="mr-2 w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Impact Weights -->
                <div class="mb-6 border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Impact Weights by Hierarchy Level</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Good Impact -->
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h5 class="text-sm font-semibold text-green-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                </svg>
                                Good Performance Impact (Points)
                            </h5>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Level 0 (Direct User)</label>
                                    <input type="number" step="0.1" wire:model="good_impact_0" class="w-full px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Level 1 (Supervisor)</label>
                                    <input type="number" step="0.1" wire:model="good_impact_1" class="w-full px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Level 2 (Manager)</label>
                                    <input type="number" step="0.1" wire:model="good_impact_2" class="w-full px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Level 3 (Asst Manager)</label>
                                    <input type="number" step="0.1" wire:model="good_impact_3" class="w-full px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Level 4 (Director)</label>
                                    <input type="number" step="0.1" wire:model="good_impact_4" class="w-full px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Bad Impact -->
                        <div class="bg-red-50 p-4 rounded-lg">
                            <h5 class="text-sm font-semibold text-red-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/>
                                </svg>
                                Bad Performance Impact (Points)
                            </h5>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Level 0 (Direct User)</label>
                                    <input type="number" step="0.1" wire:model="bad_impact_0" class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Level 1 (Supervisor)</label>
                                    <input type="number" step="0.1" wire:model="bad_impact_1" class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Level 2 (Manager)</label>
                                    <input type="number" step="0.1" wire:model="bad_impact_2" class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Level 3 (Asst Manager)</label>
                                    <input type="number" step="0.1" wire:model="bad_impact_3" class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Level 4 (Director)</label>
                                    <input type="number" step="0.1" wire:model="bad_impact_4" class="w-full px-3 py-2 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-xs text-yellow-800">
                            <strong>Note:</strong> Positive values for good performance, negative values for bad performance. 
                            Impact decreases as hierarchy level increases.
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <button type="button" wire:click="closeModal" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        {{ $editMode ? 'Update Configuration' : 'Create Configuration' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
