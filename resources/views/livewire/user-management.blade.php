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

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage staff members and their organizational roles</p>
                </div>
            </div>
            <button wire:click="showCreateForm" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New User
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
            <svg class="w-5 h-5 text-gray-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <h3 class="text-base font-semibold text-gray-900">Search & Filter</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or email..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select wire:model.live="selectedDepartment" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                <select wire:model.live="selectedPosition" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Positions</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supervisor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->phone_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->role->display_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->department->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->position->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->supervisor->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button wire:click="viewDetails({{ $user->id }})" class="text-green-600 hover:text-green-900">View</button>
                            <button wire:click="showEditForm({{ $user->id }})" class="text-blue-600 hover:text-blue-900">Edit</button>
                            <button wire:click="delete({{ $user->id }})" wire:confirm="Are you sure you want to delete this user?" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

    <!-- User Form Modal -->
    @if($showForm)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $editingUser ? 'Edit User' : 'Add New User' }}
                </h3>
                <button wire:click="resetForm" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" wire:model="name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password {{ $editingUser ? '(leave blank to keep current)' : '*' }}</label>
                        <input type="password" wire:model="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" wire:model="phone_number" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                        <select wire:model="role_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                        @error('role_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                        <select wire:model="department_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Position *</label>
                        <select wire:model="position_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Position</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                            @endforeach
                        </select>
                        @error('position_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supervisor</label>
                        <select wire:model="supervisor_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Supervisor</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}">{{ $supervisor->name }} - {{ $supervisor->position->name ?? 'No Position' }}</option>
                            @endforeach
                        </select>
                        @error('supervisor_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Active</label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" wire:click="resetForm" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        {{ $editingUser ? 'Update User' : 'Create User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- User Details Modal -->
    @if($showDetailsModal && $viewingUser)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">User Details</h3>
                <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- User Info Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6">
                <div class="flex items-start space-x-4">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-2xl">
                            {{ strtoupper(substr($viewingUser->name, 0, 2)) }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-2xl font-bold text-gray-900">{{ $viewingUser->name }}</h4>
                        <p class="text-gray-600 mt-1">{{ $viewingUser->email }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                {{ $viewingUser->role->display_name ?? 'No Role' }}
                            </span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm font-semibold rounded-full">
                                {{ $viewingUser->position->name ?? 'No Position' }}
                            </span>
                            <span class="px-3 py-1 {{ $viewingUser->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-sm font-semibold rounded-full">
                                {{ $viewingUser->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-blue-600 font-medium">Total Measurements</p>
                    <p class="text-3xl font-bold text-blue-900 mt-1">{{ $userDetails['total_measurements'] }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-green-600 font-medium">Average Score</p>
                    <p class="text-3xl font-bold text-green-900 mt-1">{{ $userDetails['avg_score'] }}/6</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm text-purple-600 font-medium">Good Logs</p>
                    <p class="text-3xl font-bold text-purple-900 mt-1">{{ $userDetails['good_logs'] }}</p>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <p class="text-sm text-red-600 font-medium">Bad Logs</p>
                    <p class="text-3xl font-bold text-red-900 mt-1">{{ $userDetails['bad_logs'] }}</p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-4">
                <nav class="-mb-px flex space-x-8">
                    <button class="border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                        Overview
                    </button>
                </nav>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Basic Info -->
                    <div class="bg-white border rounded-lg p-4">
                        <h5 class="font-bold text-gray-900 mb-3">Basic Information</h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Department:</span>
                                <span class="font-medium">{{ $viewingUser->department->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Position:</span>
                                <span class="font-medium">{{ $viewingUser->position->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium">{{ $viewingUser->phone_number ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Supervisor:</span>
                                <span class="font-medium">{{ $viewingUser->supervisor->name ?? 'None' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subordinates:</span>
                                <span class="font-medium">{{ $userDetails['subordinates_count'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Subordinates -->
                    @if($viewingUser->subordinates->count() > 0)
                    <div class="bg-white border rounded-lg p-4">
                        <h5 class="font-bold text-gray-900 mb-3">Team Members</h5>
                        <div class="space-y-2">
                            @foreach($viewingUser->subordinates as $subordinate)
                            <div class="flex items-center space-x-2 text-sm">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold text-xs">{{ strtoupper(substr($subordinate->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium">{{ $subordinate->name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $subordinate->position->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Recent Performance -->
                    <div class="bg-white border rounded-lg p-4">
                        <h5 class="font-bold text-gray-900 mb-3">Recent Performance</h5>
                        <div class="space-y-2">
                            @forelse($userDetails['recent_measurements'] as $measurement)
                            <div class="flex items-center justify-between text-sm border-b pb-2">
                                <span class="text-gray-600">{{ $measurement->measurement_date->format('M d, Y') }}</span>
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-green-600">{{ $measurement->total_score }}/6</span>
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $measurement->percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm">No measurements yet</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Logs -->
                    <div class="bg-white border rounded-lg p-4">
                        <h5 class="font-bold text-gray-900 mb-3">Recent Activity</h5>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @forelse($userDetails['recent_logs'] as $log)
                            <div class="flex items-start space-x-2 text-sm border-b pb-2">
                                <span class="px-2 py-1 {{ $log->status === 'good' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs font-semibold rounded">
                                    {{ strtoupper($log->status) }}
                                </span>
                                <div class="flex-1">
                                    <p class="text-gray-600 text-xs">{{ $log->logged_at->format('M d, h:i A') }}</p>
                                    @if($log->notes)
                                    <p class="text-gray-700 mt-1">{{ $log->notes }}</p>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm">No activity logs yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button wire:click="closeDetailsModal" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif
</div>