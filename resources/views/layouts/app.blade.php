<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sales Administration System Management' }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Fixed Header Navigation -->
        <nav class="bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg fixed top-0 left-0 right-0 z-50">
            <div class="max-w-full px-6">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden mr-4 text-white hover:text-blue-200 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-white flex items-center space-x-3">
                            <img src="{{ asset('images/logo.png') }}" alt="29 Logo" class="h-12 w-auto">
                            <span class="hidden sm:inline">Sales Administration System</span>
                            <span class="sm:hidden">29 Sales</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        @auth
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <a href="{{ route('profile') }}" class="flex items-center space-x-2 text-white hover:text-blue-200 transition-colors">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ Storage::url(Auth::user()->profile_photo) }}" alt="Profile" class="w-8 h-8 rounded-full object-cover border-2 border-white">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center border-2 border-white">
                                            <span class="text-sm font-bold">{{ substr(Auth::user()->name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                    <span class="font-medium hidden md:inline">{{ Auth::user()->name }}</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 sm:px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Logout</span>
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar and Content -->
        @auth
        <div class="flex pt-16" x-data="{ mobileMenuOpen: false }">
            <!-- Mobile Overlay -->
            <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
            
            <!-- Fixed Sidebar with Scroll -->
            <div :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'" class="w-64 bg-white shadow-lg fixed left-0 top-16 bottom-0 border-r border-gray-200 overflow-y-auto z-40 transition-transform duration-300 ease-in-out lg:translate-x-0">
                <div class="p-4">
                    <nav class="space-y-1 pb-6">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('users.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200 {{ request()->routeIs('users.index') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span>User Management</span>
                        </a>
                        <a href="{{ route('kpi.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200 {{ request()->routeIs('kpi.index') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>PER_Tracking</span>
                        </a>

                        <a href="{{ route('performance.kpi') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200 {{ request()->routeIs('performance.kpi') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>Performance KPI</span>
                        </a>
                        
                        <!-- Ranking Codes -->
                        <a href="{{ route('ranking-codes.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200 {{ request()->routeIs('ranking-codes.*') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span>Ranking Codes</span>
                        </a>

                        <!-- Sales Data -->
                        <a href="{{ route('sales.data.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200 {{ request()->routeIs('sales.data.*') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <span>Sales Data</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 lg:ml-64 p-4 sm:p-6 lg:p-8 bg-gray-50 min-h-screen">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </div>
        </div>
        @else
        <div class="p-4 sm:p-6 pt-20">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </div>
        @endauth
    </div>

    <!-- Livewire Scripts (includes Alpine.js) -->
    @livewireScripts
    
    <!-- Alpine.js (as a fallback in case Livewire's version fails to load) -->
    <script>
    // Check if Alpine is already loaded (by Livewire)
    if (!window.Alpine) {
        console.log('Alpine.js not loaded by Livewire, loading manually...');
        var alpineScript = document.createElement('script');
        alpineScript.src = 'https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js';
        alpineScript.defer = true;
        document.head.appendChild(alpineScript);
    } else {
        console.log('Alpine.js already loaded by Livewire');
    }
    </script>
    
    <!-- jQuery with fallback -->
    <script>
    (function() {
        // Try loading from CDN first
        var script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js';
        script.integrity = 'sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==';
        script.crossOrigin = 'anonymous';
        script.referrerPolicy = 'no-referrer';
        
        // If CDN fails, load local version
        script.onerror = function() {
            var localScript = document.createElement('script');
            localScript.src = "{{ asset('js/jquery-3.7.1.min.js') }}";
            document.head.appendChild(localScript);
            console.log('Loaded jQuery from local');
        };
        
        document.head.appendChild(script);
        console.log('jQuery loading from CDN');
    })();
    </script>
    
    @stack('scripts')
</body>
</html>
