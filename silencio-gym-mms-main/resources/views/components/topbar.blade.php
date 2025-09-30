<aside class="h-16 sm:h-20 flex justify-between items-center px-4 sm:px-6 py-3 sm:py-4 bg-white border-b border-gray-300">
    <div class="flex items-center gap-2 sm:gap-4">
        <!-- Mobile Toggle Button -->
        <button class="sidebar-toggle lg:hidden cursor-pointer p-2 hover:bg-gray-100 rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <!-- Page Title with Icon -->
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/icons/dashboard-icon.svg') }}" alt="Dashboard Icon" class="w-7 h-7 sm:w-9 sm:h-9" style="filter: brightness(0);">
            <h1 class="text-lg sm:text-xl font-semibold text-gray-800">{{ $slot }}</h1>
        </div>
    </div>
    
    <!-- Admin Info - Right Side -->
    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Admin Name and Role -->
        <div class="hidden md:flex items-center gap-2 text-right">
            <div>
                <div class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="text-xs text-gray-500 uppercase">{{ auth()->user()->role ?? 'ADMIN' }}</div>
            </div>
            <!-- Admin Avatar -->
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>
    </div>
</aside>