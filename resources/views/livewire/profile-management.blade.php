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
        <div class="flex items-center">
            <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
                <p class="text-sm text-gray-600 mt-1">Manage your personal information and settings</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Photo Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 text-gray-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h2 class="text-base font-semibold text-gray-900">Profile Photo</h2>
                </div>
                
                <div class="flex flex-col items-center">
                    <!-- Photo Display -->
                    <div class="relative">
                        @if($profile_photo)
                            <img src="{{ Storage::url($profile_photo) }}" alt="Profile" class="w-32 h-32 rounded-full object-cover border-4 border-indigo-100">
                        @else
                            <div class="w-32 h-32 rounded-full bg-indigo-100 flex items-center justify-center border-4 border-indigo-200">
                                <span class="text-4xl font-bold text-indigo-600">{{ substr($name, 0, 2) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Photo Upload -->
                    <div class="mt-4 w-full">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Change Photo</label>
                        <input type="file" wire:model="new_profile_photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('new_profile_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        
                        @if ($new_profile_photo)
                            <div class="mt-2">
                                <img src="{{ $new_profile_photo->temporaryUrl() }}" class="w-20 h-20 rounded-lg object-cover">
                            </div>
                        @endif
                    </div>

                    <!-- Remove Photo Button -->
                    @if($profile_photo)
                    <button wire:click="removePhoto" wire:confirm="Are you sure you want to remove your profile photo?" class="mt-3 text-sm text-red-600 hover:text-red-800">
                        Remove Photo
                    </button>
                    @endif
                </div>

                <!-- User Info Summary -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500">Position</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->position->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Department</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->department->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Role</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->role->display_name ?? ucfirst($user->role->name ?? 'User') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h2 class="text-base font-semibold text-gray-900">Personal Information</h2>
                    </div>
                    <button wire:click="openPasswordModal" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        Change Password
                    </button>
                </div>

                <form wire:submit.prevent="updateProfile">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" wire:model="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your name">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" wire:model="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your email">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" wire:model="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your phone number">
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($user->status ?? 'active') }}
                                </span>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                            <textarea wire:model="bio" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tell us about yourself..."></textarea>
                            @error('bio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Maximum 500 characters</p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- KPI Summary Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 text-gray-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h2 class="text-base font-semibold text-gray-900">Quick Stats</h2>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <p class="text-2xl font-bold text-gray-900">{{ $user->kpiMeasurements()->count() }}</p>
                        <p class="text-xs text-gray-600 mt-1">KPI Records</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <p class="text-2xl font-bold text-gray-900">{{ $user->department->users()->count() ?? 0 }}</p>
                        <p class="text-xs text-gray-600 mt-1">Team Members</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    @if($showPasswordModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Change Password</h3>
                <button wire:click="closePasswordModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="changePassword">
                <div class="space-y-4">
                    <!-- Current Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <input type="password" wire:model="current_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter current password">
                        @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" wire:model="new_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter new password">
                        @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" wire:model="new_password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Confirm new password">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" wire:click="closePasswordModal" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium text-sm">
                        Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
