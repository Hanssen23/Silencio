<x-layout>
    <x-nav></x-nav>
    <div class="flex-1 bg-white">
        <x-topbar>All Memberships</x-topbar>

        <!-- Main Content -->
        <div class="p-4 sm:p-6">
            @if(session('success'))
                <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-white border rounded-lg" style="border-color: #059669; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-lg sm:text-xl">✅</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium" style="color: #059669;">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Header Section -->
            <div class="bg-white rounded-lg border p-4 sm:p-6 lg:p-8 mb-4 sm:mb-6" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 sm:gap-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold mb-2" style="color: #1E40AF;">All Memberships</h1>
                        <p class="text-base sm:text-lg" style="color: #6B7280;">Manage member accounts and memberships</p>
                    </div>
                    <a href="{{ route('members.create') }}" 
                       class="w-full sm:w-auto px-4 sm:px-6 py-3 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2" 
                       style="background-color: #2563EB;" 
                       onmouseover="this.style.backgroundColor='#1D4ED8'" 
                       onmouseout="this.style.backgroundColor='#2563EB'">
                        <span class="text-lg sm:text-xl">➕</span>
                            Add Member
                    </a>
                </div>
                
                <!-- Filter Pills -->
                <div class="mt-4 sm:mt-6">
                    <div class="flex flex-wrap gap-2 sm:gap-3">
                        <a href="{{ route('members.index') }}" 
                           class="px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-medium transition-colors min-h-[44px] flex items-center justify-center {{ empty($selectedMembership) ? 'text-white' : 'text-gray-600' }}" 
                           style="background-color: {{ empty($selectedMembership) ? '#1E40AF' : '#F3F4F6' }};"
                           onmouseover="this.style.backgroundColor='{{ empty($selectedMembership) ? '#1E40AF' : '#E5E7EB' }}'" 
                           onmouseout="this.style.backgroundColor='{{ empty($selectedMembership) ? '#1E40AF' : '#F3F4F6' }}'">
                            All
                        </a>
                        <a href="{{ route('members.index', ['membership' => 'basic']) }}" 
                           class="px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-medium transition-colors min-h-[44px] flex items-center justify-center {{ ($selectedMembership ?? '') === 'basic' ? 'text-white' : 'text-gray-600' }}" 
                           style="background-color: {{ ($selectedMembership ?? '') === 'basic' ? '#1E40AF' : '#F3F4F6' }};"
                           onmouseover="this.style.backgroundColor='{{ ($selectedMembership ?? '') === 'basic' ? '#1E40AF' : '#E5E7EB' }}'" 
                           onmouseout="this.style.backgroundColor='{{ ($selectedMembership ?? '') === 'basic' ? '#1E40AF' : '#F3F4F6' }}'">
                            Basic
                        </a>
                        <a href="{{ route('members.index', ['membership' => 'vip']) }}" 
                           class="px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-medium transition-colors min-h-[44px] flex items-center justify-center {{ ($selectedMembership ?? '') === 'vip' ? 'text-white' : 'text-gray-600' }}" 
                           style="background-color: {{ ($selectedMembership ?? '') === 'vip' ? '#1E40AF' : '#F3F4F6' }};"
                           onmouseover="this.style.backgroundColor='{{ ($selectedMembership ?? '') === 'vip' ? '#1E40AF' : '#E5E7EB' }}'" 
                           onmouseout="this.style.backgroundColor='{{ ($selectedMembership ?? '') === 'vip' ? '#1E40AF' : '#F3F4F6' }}'">
                            VIP
                        </a>
                        <a href="{{ route('members.index', ['membership' => 'premium']) }}" 
                           class="px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-medium transition-colors min-h-[44px] flex items-center justify-center {{ ($selectedMembership ?? '') === 'premium' ? 'text-white' : 'text-gray-600' }}" 
                           style="background-color: {{ ($selectedMembership ?? '') === 'premium' ? '#1E40AF' : '#F3F4F6' }};"
                           onmouseover="this.style.backgroundColor='{{ ($selectedMembership ?? '') === 'premium' ? '#1E40AF' : '#E5E7EB' }}'" 
                           onmouseout="this.style.backgroundColor='{{ ($selectedMembership ?? '') === 'premium' ? '#1E40AF' : '#F3F4F6' }}'">
                            Premium
                        </a>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="mt-4 sm:mt-6">
                    <form method="GET" action="{{ route('members.index') }}" class="w-full max-w-full sm:max-w-md">
                        <div class="relative">
                            <input name="search" value="{{ $search ?? '' }}" type="text" 
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
            </div>

            <!-- Members Table -->
            <div class="bg-white rounded-lg border overflow-hidden" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y" style="border-color: #E5E7EB; min-width: 1000px; table-layout: fixed;">
                        <thead style="background-color: #1E40AF;">
                            <tr>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 12%;">UID</th>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 15%;">MEMBER NUMBER</th>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 12%;">MEMBERSHIP</th>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 18%;">FULL NAME</th>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 15%;">MOBILE NUMBER</th>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 20%;">EMAIL</th>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 8%;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="background-color: #FFFFFF; border-color: #E5E7EB;">
                            @forelse($members as $member)
                            <tr class="hover:bg-gray-50" style="background-color: {{ $loop->even ? '#F9FAFB' : '#FFFFFF' }};">
                                <!-- UID Column -->
                                <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm font-medium" style="color: #000000;">
                                    {{ $member->uid }}
                                </td>
                                
                                <!-- MEMBER NUMBER Column -->
                                <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm" style="color: #000000;">
                                    MEM{{ str_pad($member->id, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                
                                <!-- MEMBERSHIP Column -->
                                <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm">
                                    @php
                                        $currentPlan = $member->currentMembershipPeriod ? $member->currentMembershipPeriod->plan_type : null;
                                        $isActive = $member->currentMembershipPeriod && $member->currentMembershipPeriod->is_active;
                                        $hasPayments = $member->payments()->where('status', 'completed')->exists();
                                        $planFromPayments = $member->payments()->where('status', 'completed')->latest()->first();
                                    @endphp
                                    
                                    @if($currentPlan && $isActive)
                                        @php
                                            // Normalize plan type to lowercase for consistent color mapping
                                            $normalizedPlan = strtolower($currentPlan);
                                            $durationType = $member->currentMembershipPeriod ? $member->currentMembershipPeriod->duration_type : null;
                                            $planColors = [
                                                'basic' => '#059669',
                                                'vip' => '#F59E0B', 
                                                'premium' => '#F59E0B'
                                            ];
                                            $planColor = $planColors[$normalizedPlan] ?? '#059669';
                                            
                                            // Create combined text for the badge
                                            $badgeText = ucfirst($currentPlan);
                                        @endphp
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full text-white" 
                                              style="background-color: {{ $planColor }};">
                                            {{ $badgeText }}
                                        </span>
                                    @elseif($hasPayments && $planFromPayments)
                                        @php
                                            // Show plan from latest payment if no active membership period
                                            $paymentPlan = $planFromPayments->plan_type;
                                            $paymentDuration = $planFromPayments->duration_type;
                                            $normalizedPlan = strtolower($paymentPlan);
                                            $planColors = [
                                                'basic' => '#059669',
                                                'vip' => '#F59E0B', 
                                                'premium' => '#F59E0B'
                                            ];
                                            $planColor = $planColors[$normalizedPlan] ?? '#059669';
                                            
                                            // Create combined text for the badge
                                            $badgeText = ucfirst($paymentPlan);
                                        @endphp
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full text-white" 
                                              style="background-color: {{ $planColor }};">
                                            {{ $badgeText }}
                                        </span>
                                    @else
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full text-gray-600 bg-gray-200">
                                            Not Subscribed
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- FULL NAME Column -->
                                <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm font-medium" style="color: #000000;">
                                    {{ $member->first_name }} {{ $member->last_name }}
                                </td>
                                
                                <!-- MOBILE NUMBER Column -->
                                <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm" style="color: #000000;">
                                    @if($member->mobile_number)
                                        @php
                                            // Remove any existing +63 prefix and clean the number
                                            $cleanNumber = preg_replace('/^\+63\s*/', '', $member->mobile_number);
                                            $cleanNumber = preg_replace('/\D/', '', $cleanNumber); // Remove non-digits
                                            
                                            // Format as +63 XXX XXX XXXX
                                            if (strlen($cleanNumber) >= 10) {
                                                $formatted = '+63 ' . substr($cleanNumber, 0, 3) . ' ' . substr($cleanNumber, 3, 3) . ' ' . substr($cleanNumber, 6);
                                            } else {
                                                $formatted = '+63 ' . $cleanNumber;
                                            }
                                        @endphp
                                        {{ $formatted }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                
                                <!-- EMAIL Column -->
                                <td class="px-2 sm:px-3 py-2 sm:py-3 text-xs sm:text-sm" style="color: #000000; overflow: hidden; text-overflow: ellipsis;">
                                    <span title="{{ $member->email }}">{{ $member->email }}</span>
                                </td>
                                <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('members.profile', $member->id) }}" class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200" title="View Profile">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('members.edit', $member->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200" title="Edit Member">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button onclick="deleteMember({{ $member->id }}, '{{ $member->first_name }} {{ $member->last_name }}')" class="text-red-600 hover:text-red-900 transition-colors duration-200" title="Delete Member">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center" style="color: #6B7280;">
                                    <div class="flex flex-col items-center">
                                        <div class="text-6xl mb-4">👥</div>
                                        <p class="text-lg font-medium mb-2" style="color: #000000;">No members found</p>
                                        <p class="mb-4">Get started by adding your first member.</p>
                                        <a href="{{ route('members.create') }}" 
                                           class="px-6 py-3 text-white rounded-lg font-medium transition-colors duration-200 flex items-center gap-2" 
                                           style="background-color: #2563EB;" 
                                           onmouseover="this.style.backgroundColor='#1D4ED8'" 
                                           onmouseout="this.style.backgroundColor='#2563EB'">
                                            <span class="text-xl">➕</span>
                                            Add Member
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($members->hasPages())
                <div class="bg-white px-2 sm:px-4 lg:px-6 py-2 sm:py-3 lg:py-4 border-t" style="border-color: #E5E7EB;">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($members->onFirstPage())
                                <span class="relative inline-flex items-center px-2 sm:px-4 py-2 border text-xs sm:text-sm font-medium rounded-md" style="border-color: #E5E7EB; color: #6B7280;">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $members->previousPageUrl() }}" 
                                   class="relative inline-flex items-center px-2 sm:px-4 py-2 border text-xs sm:text-sm font-medium rounded-md transition-colors" 
                                   style="border-color: #E5E7EB; color: #6B7280;" 
                                   onmouseover="this.style.backgroundColor='#F3F4F6'" 
                                   onmouseout="this.style.backgroundColor='transparent'">
                                    Previous
                                </a>
                            @endif

                            @if($members->hasMorePages())
                                <a href="{{ $members->nextPageUrl() }}" 
                                   class="ml-3 relative inline-flex items-center px-2 sm:px-4 py-2 border text-xs sm:text-sm font-medium rounded-md transition-colors" 
                                   style="border-color: #E5E7EB; color: #6B7280;" 
                                   onmouseover="this.style.backgroundColor='#F3F4F6'" 
                                   onmouseout="this.style.backgroundColor='transparent'">
                                    Next
                                </a>
                            @else
                                <span class="ml-3 relative inline-flex items-center px-2 sm:px-4 py-2 border text-xs sm:text-sm font-medium rounded-md" style="border-color: #E5E7EB; color: #6B7280;">
                                    Next
                                </span>
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs sm:text-sm" style="color: #6B7280;">
                                    Showing
                                    <span class="font-medium" style="color: #000000;">{{ $members->firstItem() }}</span>
                                    to
                                    <span class="font-medium" style="color: #000000;">{{ $members->lastItem() }}</span>
                                    of
                                    <span class="font-medium" style="color: #000000;">{{ $members->total() }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                    {{ $members->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Member Delete Confirmation Modal -->
    <div id="memberDeleteConfirmModal" class="fixed inset-0 flex items-center justify-center p-2 sm:p-4 hidden z-50">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full transform transition-all duration-300 scale-95 opacity-0 border border-gray-200" 
             id="memberDeleteConfirmModalContent"
             style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
            <div class="p-4 sm:p-6 text-center">
                <div class="mb-4 sm:mb-6">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Are you sure you want to delete this member?</h3>
                    <p id="memberDeleteConfirmMessage" class="text-sm sm:text-base text-gray-600">This action cannot be undone.</p>
                </div>
                <div class="flex flex-col sm:flex-row justify-center gap-3 pt-4" style="background-color: #F9FAFB;">
                    <button onclick="cancelMemberDelete()" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gray-100 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                        Cancel
                    </button>
                    <button onclick="confirmMemberDelete()" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 font-medium">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let pendingMemberDelete = null;

        function deleteMember(memberId, memberName) {
            const modal = document.getElementById('memberDeleteConfirmModal');
            const content = document.getElementById('memberDeleteConfirmModalContent');

            // Store the member info for deletion
            pendingMemberDelete = {
                id: memberId,
                name: memberName
            };
            
            // Update modal message
            document.getElementById('memberDeleteConfirmMessage').textContent = `Are you sure you want to delete "${memberName}"? This action cannot be undone.`;
            
            // Show confirmation modal
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function cancelMemberDelete() {
            const modal = document.getElementById('memberDeleteConfirmModal');
            const content = document.getElementById('memberDeleteConfirmModalContent');
            
            // Trigger close animation
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                pendingMemberDelete = null;
            }, 300);
        }

        function confirmMemberDelete() {
            if (!pendingMemberDelete) return;

            // Create a form and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/members/${pendingMemberDelete.id}`;
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            // Add DELETE method
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }

        // Close modal when clicking outside
        document.getElementById('memberDeleteConfirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                cancelMemberDelete();
            }
        });

        // Auto-refresh members list every 30 seconds to ensure real-time updates
        let refreshInterval;
        
        function startAutoRefresh() {
            // Clear any existing interval
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
            
            // Set up new interval for auto-refresh
            refreshInterval = setInterval(function() {
                console.log('Auto-refreshing members list...');
                window.location.reload();
            }, 30000); // Refresh every 30 seconds
        }
        
        function stopAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
                refreshInterval = null;
            }
        }
        
        // Start auto-refresh when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startAutoRefresh();
            
            // Stop auto-refresh when user is interacting with the page
            let userActivityTimeout;
            document.addEventListener('mousemove', function() {
                stopAutoRefresh();
                clearTimeout(userActivityTimeout);
                userActivityTimeout = setTimeout(function() {
                    startAutoRefresh();
                }, 60000); // Restart auto-refresh 1 minute after user stops interacting
            });
        });
        
        // Clean up interval when page is unloaded
        window.addEventListener('beforeunload', function() {
            stopAutoRefresh();
        });
    </script>
</x-layout>