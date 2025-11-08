<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Group Management</h1>
        <p class="mt-2 text-sm text-gray-600">Manage teams, projects, and committees</p>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters and Search -->
    <div class="mb-6 flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0 gap-4">
        <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
            <!-- Search -->
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Search groups..."
                class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
            
            <!-- Filter by Type -->
            <select 
                wire:model.live="filterType"
                class="w-full sm:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="">All Types</option>
                <option value="team">Team</option>
                <option value="project">Project</option>
                <option value="committee">Committee</option>
            </select>

            <!-- Filter by Branch -->
            <select 
                wire:model.live="filterBranch"
                class="w-full sm:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="">All Branches</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        <button 
            wire:click="openCreateModal"
            class="flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Create Group</span>
        </button>
    </div>

    <!-- Groups Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($groups as $group)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                <!-- Header with Type Badge -->
                <div class="p-4 {{ $group->group_type === 'team' ? 'bg-gradient-to-r from-blue-500 to-blue-600' : ($group->group_type === 'project' ? 'bg-gradient-to-r from-purple-500 to-purple-600' : 'bg-gradient-to-r from-green-500 to-green-600') }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-white">{{ $group->name }}</h3>
                            <p class="text-sm text-white opacity-90">{{ $group->code }}</p>
                        </div>
                        <span class="px-2 py-1 bg-white bg-opacity-20 text-white text-xs font-semibold rounded">
                            {{ ucfirst($group->group_type) }}
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-4">
                    <!-- Description -->
                    @if($group->description)
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $group->description }}</p>
                    @endif

                    <!-- Leader -->
                    <div class="mb-3">
                        <p class="text-xs text-gray-500 mb-1">Leader</p>
                        @if($group->leader)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-yellow-700">{{ substr($group->leader->name, 0, 2) }}</span>
                            </div>
                            <div class="ml-2">
                                <p class="text-sm font-medium text-gray-900">{{ $group->leader->name }}</p>
                                <p class="text-xs text-gray-500">{{ $group->leader->position?->name }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-sm text-gray-400">No leader assigned</p>
                        @endif
                    </div>

                    <!-- Branch -->
                    @if($group->branch)
                    <div class="mb-3">
                        <p class="text-xs text-gray-500 mb-1">Branch</p>
                        <p class="text-sm text-gray-700">{{ $group->branch->name }}</p>
                    </div>
                    @endif

                    <!-- Members Count -->
                    <div class="flex items-center justify-between mb-4">
                        <button 
                            wire:click="openMembersModal({{ $group->id }})"
                            class="flex items-center space-x-2 text-blue-600 hover:text-blue-800"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="text-sm font-medium">{{ $group->members_count }} {{ Str::plural('member', $group->members_count) }}</span>
                        </button>

                        <button 
                            wire:click="toggleStatus({{ $group->id }})"
                            class="px-2 py-1 rounded text-xs font-medium {{ $group->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                        >
                            {{ $group->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-2 pt-3 border-t border-gray-200">
                        <button 
                            wire:click="openEditModal({{ $group->id }})"
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                            title="Edit"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button 
                            wire:click="delete({{ $group->id }})"
                            wire:confirm="Are you sure you want to delete this group?"
                            class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors"
                            title="Delete"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="mt-2 text-gray-500">No groups found</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $groups->links() }}
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">
                    {{ $isEditing ? 'Edit Group' : 'Create New Group' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Group Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Group Name *</label>
                        <input 
                            type="text" 
                            wire:model="name"
                            id="name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., Sales Team A"
                        >
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Group Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Group Code *</label>
                        <input 
                            type="text" 
                            wire:model="code"
                            id="code"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., ST-A"
                        >
                        @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Group Type -->
                    <div>
                        <label for="group_type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select 
                            wire:model="group_type"
                            id="group_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="team">Team</option>
                            <option value="project">Project</option>
                            <option value="committee">Committee</option>
                        </select>
                        @error('group_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Leader -->
                    <div>
                        <label for="leader_id" class="block text-sm font-medium text-gray-700 mb-2">Leader</label>
                        <select 
                            wire:model="leader_id"
                            id="leader_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">Select Leader</option>
                            @foreach($leaders as $leader)
                                <option value="{{ $leader->id }}">{{ $leader->name }} - {{ $leader->position?->name }}</option>
                            @endforeach
                        </select>
                        @error('leader_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Branch -->
                    <div>
                        <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                        <select 
                            wire:model="branch_id"
                            id="branch_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">Select Branch (Optional)</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea 
                            wire:model="description"
                            id="description"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter group description"
                        ></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                wire:model="is_active"
                                id="is_active"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
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
                        {{ $isEditing ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Members Management Modal -->
    @if($showMembersModal && $editingGroup)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">
                    Manage Members - {{ $editingGroup->name }}
                </h3>
                <button wire:click="closeMembersModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Note:</strong> Select users to add to this group and assign their roles.
                </p>
            </div>

            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($availableUsers as $user)
                <div class="flex items-center justify-between p-3 border rounded-lg {{ in_array($user->id, $selectedMembers) ? 'bg-blue-50 border-blue-300' : 'border-gray-200' }}">
                    <div class="flex items-center space-x-3">
                        <input 
                            type="checkbox" 
                            wire:model="selectedMembers" 
                            value="{{ $user->id }}"
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold text-white">{{ substr($user->name, 0, 2) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->position?->name }} - {{ $user->department?->name }}</p>
                            </div>
                        </div>
                    </div>

                    @if(in_array($user->id, $selectedMembers))
                    <select 
                        wire:model="memberRoles.{{ $user->id }}"
                        class="px-3 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="member">Member</option>
                        <option value="leader">Leader</option>
                        <option value="coordinator">Coordinator</option>
                    </select>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    <strong>{{ count($selectedMembers) }}</strong> members selected
                </div>
                <div class="flex space-x-3">
                    <button 
                        wire:click="closeMembersModal"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="updateMembers"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                    >
                        Update Members
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
