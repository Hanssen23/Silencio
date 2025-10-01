@props(['action', 'search' => '', 'selectedMembership' => ''])

<!-- Search Bar -->
<div class="mt-4 sm:mt-6">
    <form method="GET" action="{{ $action }}" class="w-full max-w-full sm:max-w-md">
        <div class="relative">
            <input name="search" value="{{ $search }}" type="text" 
                   placeholder="Search by name, email, or member number..." 
                   class="w-full px-3 sm:px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm sm:text-base min-h-[44px]" 
                   style="border-color: #E5E7EB; color: #000000;">
            <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
            @if(!empty($selectedMembership))
                <input type="hidden" name="membership" value="{{ $selectedMembership }}">
            @endif
        </div>
    </form>
</div>
