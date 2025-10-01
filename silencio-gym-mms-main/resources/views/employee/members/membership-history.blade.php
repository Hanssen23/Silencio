<x-layout>
    <x-nav-employee></x-nav-employee>
    <div class="flex-1 bg-white">
        <x-topbar>Membership History</x-topbar>

        <!-- Main Content -->
        <div class="p-6">
            <!-- Header with Back Button -->
            <div class="mb-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('employee.members') }}" class="flex items-center gap-2 transition-colors duration-200" style="color: #6B7280;" onmouseover="this.style.color='#000000'" onmouseout="this.style.color='#6B7280'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="font-medium">Back to Members</span>
                    </a>
                </div>
                <h2 class="text-2xl font-bold mt-2" style="color: #1E40AF;">{{ $member->full_name }}'s Membership History</h2>
            </div>

            <!-- Membership History Table -->
            <div class="bg-white rounded-lg border overflow-hidden" style="border-color: #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y" style="border-color: #E5E7EB;">
                        <thead style="background-color: #1E40AF;">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Plan Type</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Expiration Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Payment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="background-color: #FFFFFF; border-color: #E5E7EB;">
                            @forelse($membershipPeriods as $period)
                            <tr class="hover:bg-gray-50" style="background-color: {{ $loop->even ? '#F9FAFB' : '#FFFFFF' }};">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: #000000;">
                                    {{ ucfirst($period->plan_type) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #000000;">
                                    {{ ucfirst($period->duration_type) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #000000;">
                                    {{ $period->start_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #000000;">
                                    {{ $period->expiration_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $period->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $period->is_active ? 'Active' : 'Expired' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #000000;">
                                    @if($period->payment)
                                        <div>
                                            <p class="font-medium">₱{{ number_format($period->payment->amount, 2) }}</p>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $period->payment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($period->payment->status) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-500">No payment record</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center" style="color: #6B7280;">
                                    <div class="flex flex-col items-center">
                                        <div class="text-6xl mb-4">📋</div>
                                        <p class="text-lg font-medium mb-2" style="color: #000000;">No membership history found</p>
                                        <p class="mb-4">This member hasn't purchased any membership plans yet.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($membershipPeriods->hasPages())
                <div class="bg-white px-6 py-4 border-t" style="border-color: #E5E7EB;">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($membershipPeriods->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md" style="border-color: #E5E7EB; color: #6B7280;">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $membershipPeriods->previousPageUrl() }}" 
                                   class="relative inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md transition-colors" 
                                   style="border-color: #E5E7EB; color: #6B7280;" 
                                   onmouseover="this.style.backgroundColor='#F3F4F6'" 
                                   onmouseout="this.style.backgroundColor='transparent'">
                                    Previous
                                </a>
                            @endif

                            @if($membershipPeriods->hasMorePages())
                                <a href="{{ $membershipPeriods->nextPageUrl() }}" 
                                   class="ml-3 relative inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md transition-colors" 
                                   style="border-color: #E5E7EB; color: #6B7280;" 
                                   onmouseover="this.style.backgroundColor='#F3F4F6'" 
                                   onmouseout="this.style.backgroundColor='transparent'">
                                    Next
                                </a>
                            @else
                                <span class="ml-3 relative inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md" style="border-color: #E5E7EB; color: #6B7280;">
                                    Next
                                </span>
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm" style="color: #6B7280;">
                                    Showing
                                    <span class="font-medium" style="color: #000000;">{{ $membershipPeriods->firstItem() }}</span>
                                    to
                                    <span class="font-medium" style="color: #000000;">{{ $membershipPeriods->lastItem() }}</span>
                                    of
                                    <span class="font-medium" style="color: #000000;">{{ $membershipPeriods->total() }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                {{ $membershipPeriods->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>
