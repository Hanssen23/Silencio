<x-layout>
    <x-nav></x-nav>
    <div class="flex-1 bg-white">
        <x-topbar>All Payments</x-topbar>

        <div class="bg-white min-h-screen p-4 sm:p-6">
            <!-- Header with Quick Actions -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-lg shadow-sm border p-4 sm:p-6 lg:p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 sm:mb-8 gap-4">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-bold" style="color: #1E40AF;">Payment Records</h2>
                            <p class="text-base sm:text-lg mt-2" style="color: #6B7280;">View and manage all payment transactions</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-4">
                            <a href="{{ route('membership.manage-member') }}" class="inline-flex items-center justify-center px-4 sm:px-6 py-3 text-white rounded-lg transition-colors shadow-sm min-h-[44px] w-full sm:w-auto" style="background-color: #2563EB;" onmouseover="this.style.backgroundColor='#1D4ED8'" onmouseout="this.style.backgroundColor='#2563EB'">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="text-sm sm:text-base">New Payment</span>
                            </a>
                            <a href="{{ route('membership.plans.index') }}" class="inline-flex items-center justify-center px-4 sm:px-6 py-3 text-white rounded-lg transition-colors shadow-sm min-h-[44px] w-full sm:w-auto" style="background-color: #6B7280;" onmouseover="this.style.backgroundColor='#4B5563'" onmouseout="this.style.backgroundColor='#6B7280'">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm sm:text-base">Manage Plans</span>
                            </a>
                            <button onclick="previewCsv()" class="inline-flex items-center justify-center px-4 sm:px-6 py-3 text-white rounded-lg transition-colors shadow-sm min-h-[44px] w-full sm:w-auto" style="background-color: #059669;" onmouseover="this.style.backgroundColor='#047857'" onmouseout="this.style.backgroundColor='#059669'">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span class="text-sm sm:text-base">Preview CSV</span>
                            </button>
                            <a href="{{ route('membership.payments.export_csv', request()->query()) }}" class="inline-flex items-center justify-center px-4 sm:px-6 py-3 text-white rounded-lg transition-colors shadow-sm min-h-[44px] w-full sm:w-auto" style="background-color: #6B7280;" onmouseover="this.style.backgroundColor='#4B5563'" onmouseout="this.style.backgroundColor='#6B7280'">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm sm:text-base">Download CSV</span>
                            </a>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div class="bg-white border rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow" style="border-color: #059669; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div class="flex items-center">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center mr-3 sm:mr-4" style="background-color: #059669;">
                                    <span class="text-xl sm:text-2xl">✅</span>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-medium" style="color: #059669;">Completed</p>
                                    <p class="text-2xl sm:text-3xl font-bold" style="color: #000000;">{{ $payments->where('status', 'completed')->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow" style="border-color: #059669; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div class="flex items-center">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center mr-3 sm:mr-4" style="background-color: #059669;">
                                    <span class="text-xl sm:text-2xl font-bold" style="color: #FFFFFF;">₱</span>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-medium" style="color: #059669;">Total Revenue</p>
                                    <p class="text-2xl sm:text-3xl font-bold" style="color: #000000;">₱{{ number_format($payments->where('status', 'completed')->sum('amount'), 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Printing Features Info -->
                    <div class="mt-6 sm:mt-8 p-4 sm:p-6 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex flex-col sm:flex-row items-start gap-3 sm:gap-0">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base sm:text-lg font-semibold text-blue-900 mb-2 sm:mb-3">Payment Printing Features</h3>
                                <div class="text-blue-800 space-y-2 sm:space-y-3">
                                    <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                                        <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                        <div class="text-sm sm:text-base">
                                        <strong>Print Receipts:</strong> Click the "Print" button in the actions column or use the "Print Receipt" button in payment details to generate professional receipts
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                                        <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <div class="text-sm sm:text-base">
                                        <strong>Export to CSV:</strong> Use the "Export CSV" button to download all payment data with current filters applied
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-lg shadow-sm border p-4 sm:p-6 lg:p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <h3 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6" style="color: #1E40AF;">Filters & Search</h3>
                    <form method="GET" action="{{ route('membership.payments.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium mb-2 sm:mb-3" style="color: #374151;">Plan Type</label>
                            <select name="plan_type" class="w-full px-3 sm:px-4 py-2 sm:py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-xs sm:text-sm" style="border-color: #E5E7EB;">
                                <option value="">All Plans</option>
                                <option value="basic" {{ request('plan_type') === 'basic' ? 'selected' : '' }}>Basic</option>
                                <option value="vip" {{ request('plan_type') === 'vip' ? 'selected' : '' }}>VIP</option>
                                <option value="premium" {{ request('plan_type') === 'premium' ? 'selected' : '' }}>Premium</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium mb-2 sm:mb-3" style="color: #374151;">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-xs sm:text-sm" style="border-color: #E5E7EB;">
                        </div>
                        <div class="sm:col-span-2 lg:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium mb-2 sm:mb-3" style="color: #374151;">Search</label>
                            <div class="flex space-x-2 sm:space-x-3">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by member name, email..." class="flex-1 px-3 sm:px-4 py-2 sm:py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-xs sm:text-sm" style="border-color: #E5E7EB;">
                                <button type="submit" class="px-3 sm:px-6 py-2 sm:py-3 text-white rounded-lg transition-colors shadow-sm min-h-[44px]" style="background-color: #2563EB;" onmouseover="this.style.backgroundColor='#1D4ED8'" onmouseout="this.style.backgroundColor='#2563EB'">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Filter Actions -->
                    <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                        <button type="submit" form="filterForm" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-white rounded-lg transition-colors text-xs sm:text-sm min-h-[44px]" style="background-color: #2563EB;" onmouseover="this.style.backgroundColor='#1D4ED8'" onmouseout="this.style.backgroundColor='#2563EB'">
                            Apply
                        </button>
                        @if(request('plan_type') || request('date') || request('search'))
                        <a href="{{ route('membership.payments.index') }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-xs sm:text-sm min-h-[44px]" style="border-color: #E5E7EB;">
                            Reset
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y" style="border-color: #E5E7EB; min-width: 1000px; table-layout: fixed;">
                        <thead style="background-color: #1E40AF;">
                            <tr>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 20%;">MEMBER</th>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 15%;">PLAN & DURATION</th>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-right text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 12%;">AMOUNT</th>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 18%;">PAYMENT DATE</th>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 20%;">MEMBERSHIP PERIOD</th>
                                <th class="px-2 sm:px-3 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold text-white uppercase tracking-wider" style="width: 15%;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="background-color: #FFFFFF; border-color: #E5E7EB;">
                            @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors" style="background-color: {{ $loop->even ? '#F9FAFB' : '#FFFFFF' }};">
                                <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center" style="background-color: #1E40AF;">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-2 sm:ml-3">
                                            <div class="text-xs sm:text-sm font-semibold" style="color: #000000;">{{ $payment->member->full_name }}</div>
                                            <div class="text-xs sm:text-sm" style="color: #6B7280;">{{ $payment->member->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm" style="color: #000000;">
                                        <span class="font-semibold">{{ $payment->plan_type === 'vip' ? 'VIP' : ucfirst($payment->plan_type) }}</span>
                                        <span style="color: #6B7280;" class="mx-1 sm:mx-2">-</span>
                                        <span class="font-semibold">{{ ucfirst($payment->duration_type) }}</span>
                                    </div>
                                </td>
                                <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap text-right">
                                    <div class="text-xs sm:text-sm font-bold" style="color: #000000;">₱{{ number_format($payment->amount, 2) }}</div>
                                </td>
                                <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm" style="color: #000000;">
                                        <div class="font-semibold">{{ $payment->payment_date->format('M d, Y') }}</div>
                                        <div class="text-xs" style="color: #6B7280;">{{ $payment->payment_time ? \Carbon\Carbon::createFromFormat('H:i:s', $payment->payment_time)->format('g:i A') : 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm" style="color: #000000;">
                                        @if($payment->membership_start_date && $payment->membership_expiration_date)
                                            <div class="font-semibold">{{ $payment->membership_start_date->format('M d, Y') }}</div>
                                            <div class="text-xs" style="color: #6B7280;">to {{ $payment->membership_expiration_date->format('M d, Y') }}</div>
                                        @else
                                            <span class="text-xs" style="color: #6B7280;">N/A</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-2 sm:px-3 py-2 sm:py-3 whitespace-nowrap text-xs sm:text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button onclick="viewPaymentDetails({{ $payment->id }})" class="text-blue-600 hover:text-blue-900 transition-colors duration-200" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <a href="{{ route('membership.payments.print', $payment->id) }}" target="_blank" class="text-purple-600 hover:text-purple-900 transition-colors duration-200" title="Print Receipt">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center" style="color: #6B7280;">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 mb-6" style="color: #6B7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-xl font-semibold mb-3" style="color: #000000;">No payments found</p>
                                        <p class="text-lg mb-6" style="color: #6B7280;">Get started by processing your first payment</p>
                                        <a href="{{ route('membership.manage-member') }}" class="inline-flex items-center px-8 py-4 text-white rounded-lg transition-colors shadow-sm" style="background-color: #2563EB;" onmouseover="this.style.backgroundColor='#1D4ED8'" onmouseout="this.style.backgroundColor='#2563EB'">
                                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Process Payment
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($payments->hasPages())
            <div class="mt-6 sm:mt-8">
                <div class="bg-white rounded-lg shadow-sm border px-4 sm:px-6 lg:px-8 py-4 sm:py-6" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    {{ $payments->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Receipt Preview Modal -->
    <div id="receiptPreviewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-auto">
            <!-- Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Selected Member</h2>
                <button onclick="closeReceiptModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Member Number -->
            <div class="px-6 py-4 bg-gray-50">
                <div class="text-sm text-gray-600">Member Number</div>
                <div class="text-lg font-semibold text-gray-900" id="modal-member-number"></div>
            </div>
            
            <!-- Plan Options -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Plan Options</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 cursor-pointer" onclick="selectPlan('basic')">
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-900">Basic</div>
                            <div class="text-2xl font-bold text-green-600">₱1,500</div>
                            <div class="text-sm text-gray-600">Monthly</div>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 cursor-pointer" onclick="selectPlan('premium')">
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-900">Premium</div>
                            <div class="text-2xl font-bold text-green-600">₱2,500</div>
                            <div class="text-sm text-gray-600">Monthly</div>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 cursor-pointer" onclick="selectPlan('vip')">
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-900">VIP</div>
                            <div class="text-2xl font-bold text-green-600">₱3,500</div>
                            <div class="text-sm text-gray-600">Monthly</div>
                        </div>
                    </div>
                </div>
                
                <!-- Duration Selection -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                    <select id="duration-select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="annually">Annually</option>
                    </select>
                </div>
            </div>
            
            <!-- Membership Details -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Membership Details</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Membership Start Date</label>
                        <input type="date" id="start-date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">Ripped Body Anytime</div>
                        <div class="text-sm text-gray-600">Gym Facility Access</div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Details -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment ID:</span>
                        <span class="font-semibold" id="payment-id"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date:</span>
                        <span class="font-semibold" id="payment-date"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Time:</span>
                        <span class="font-semibold" id="payment-time"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Method:</span>
                        <span class="font-semibold">Cash</span>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-2">Membership Details</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Plan Type:</span>
                            <span class="font-semibold" id="plan-type"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-semibold" id="plan-duration"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Start Date:</span>
                            <span class="font-semibold" id="membership-start"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Purchase Date:</span>
                            <span class="font-semibold" id="purchase-date"></span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-2">Amount</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Original Amount:</span>
                            <span class="font-semibold" id="original-amount"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Discount:</span>
                            <span class="font-semibold text-green-600" id="discount-amount"></span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
                            <span>Total:</span>
                            <span id="total-amount"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="p-6 flex justify-end space-x-4">
                <button onclick="closeReceiptModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button onclick="confirmPayment()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Confirm Payment & Access Membership
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Details Side Panel -->
    <div id="paymentDetailsModal" class="fixed inset-0 flex items-center justify-center p-2 sm:p-4 hidden z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto border border-gray-200" style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200" style="background-color: #1E40AF;">
                <h3 class="text-lg sm:text-xl font-bold text-white">Payment Details</h3>
                <button onclick="closePaymentDetailsModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            <div id="paymentDetailsContent" class="p-4 sm:p-6">
                    <!-- Content will be loaded here -->
            </div>
        </div>
    </div>



    <script>
        function viewPaymentDetails(paymentId) {
            // Show loading state
            document.getElementById('paymentDetailsContent').innerHTML = `
                <div class="flex items-center justify-center py-12">
                    <svg class="animate-spin w-10 h-10 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-3 text-gray-600 text-lg">Loading payment details...</span>
                </div>
            `;
            
            // Show side panel instantly
            document.getElementById('paymentDetailsModal').classList.remove('hidden');
            
            // Fetch payment details
            fetch(`/membership/payments/${paymentId}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayPaymentDetails(data.payment, data.membership_period);
                    } else {
                        document.getElementById('paymentDetailsContent').innerHTML = `
                            <div class="text-center py-12 text-red-600">
                                <p class="text-lg">Error loading payment details</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('paymentDetailsContent').innerHTML = `
                        <div class="text-center py-12 text-red-600">
                            <p class="text-lg">Error loading payment details</p>
                        </div>
                    `;
                });
        }

        function displayPaymentDetails(payment, membershipPeriod) {
            const content = `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Payment Information -->
                    <div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-6">Payment Information</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-600 font-medium">Payment ID:</span>
                                <span class="font-semibold text-gray-900">#${payment.id}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-600 font-medium">Amount:</span>
                                <span class="font-bold text-green-600 text-xl">₱${parseFloat(payment.amount).toFixed(2)}</span>
                            </div>

                            <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-600 font-medium">Payment Date:</span>
                                <span class="font-semibold text-gray-900">${formatDate(payment.payment_date)}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-600 font-medium">Payment Time:</span>
                                <span class="font-semibold text-gray-900">${formatTime(payment.payment_time)}</span>
                            </div>

                            <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-600 font-medium">Plan Type:</span>
                                <span class="font-semibold text-gray-900">${payment.plan_type === 'vip' ? 'VIP' : payment.plan_type.charAt(0).toUpperCase() + payment.plan_type.slice(1)}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-600 font-medium">Duration:</span>
                                <span class="font-semibold text-gray-900">${payment.duration_type.charAt(0).toUpperCase() + payment.duration_type.slice(1)}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Member Information -->
                    <div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-6">Member Information</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-600 font-medium">Name:</span>
                                <span class="font-semibold text-gray-900">${payment.member.first_name} ${payment.member.last_name}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-600 font-medium">Member #:</span>
                                <span class="font-semibold text-gray-900">${payment.member.member_number}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-600 font-medium">Email:</span>
                                <span class="font-semibold text-gray-900">${payment.member.email}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 px-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-600 font-medium">Mobile:</span>
                                <span class="font-semibold text-gray-900">${payment.member.mobile_number || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Membership Period -->
                    <div class="lg:col-span-2">
                        <h4 class="text-xl font-semibold text-gray-900 mb-6">Membership Period</h4>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 mb-2">Start Date</div>
                                    <div class="font-semibold text-green-600 text-lg">${formatDate(payment.membership_start_date)}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 mb-2">Expiration Date</div>
                                    <div class="font-semibold text-red-600 text-lg">${formatDate(payment.membership_expiration_date)}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    ${payment.notes ? `
                    <div class="lg:col-span-2">
                        <h4 class="text-xl font-semibold text-gray-900 mb-6">Notes</h4>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <p class="text-gray-700 text-lg">${payment.notes}</p>
                        </div>
                    </div>
                    ` : ''}
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-end space-x-4">
                        <a href="/membership/payments/${payment.id}/print" target="_blank" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Receipt
                        </a>

                    </div>
                </div>
            `;
            
            document.getElementById('paymentDetailsContent').innerHTML = content;
        }

        function closePaymentDetailsModal() {
            document.getElementById('paymentDetailsModal').classList.add('hidden');
        }

        function updatePaymentStatus(paymentId, status) {
            // Show custom confirmation modal
            showConfirmationModal(paymentId, status);
        }

        function updatePaymentStatusDirect(paymentId, status) {
            // Show loading state
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Updating...';
            button.disabled = true;

            fetch(`/membership/payments/${paymentId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert(data.message);
                    // Close the payment details modal if it's open
                    closePaymentDetailsModal();
                    // Reload the page to reflect changes
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                    button.textContent = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the payment status');
                button.textContent = originalText;
                button.disabled = false;
            });
        }



        // Close side panel when clicking outside
        document.getElementById('paymentDetailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentDetailsModal();
            }
        });



        // Date formatting functions
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return 'N/A';
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function formatTime(timeString) {
            if (!timeString) return 'N/A';
            try {
                // Handle HH:mm:ss format
                const [hours, minutes, seconds] = timeString.split(':');
                if (!hours || !minutes || !seconds) return 'N/A';
                
                const date = new Date();
                date.setHours(parseInt(hours), parseInt(minutes), parseInt(seconds));
                
                return date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });
            } catch (e) {
                return 'N/A';
            }
        }


        // Receipt Preview Functions
        function showReceiptPreview(paymentId) {
            // Show modal
            document.getElementById('receiptPreviewModal').classList.remove('hidden');
            
            // Fetch payment details
            fetch(`/membership/payments/${paymentId}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fillReceiptPreview(data.payment);
                    } else {
                        alert('Error loading payment details');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading payment details');
                });
        }

        function fillReceiptPreview(payment) {
            // Fill member details
            document.getElementById('modal-member-number').textContent = payment.member.member_number;
            
            // Select plan
            selectPlan(payment.plan_type);
            
            // Set duration
            document.getElementById('duration-select').value = payment.duration_type;
            
            // Set dates
            document.getElementById('start-date').value = payment.membership_start_date;
            document.getElementById('payment-id').textContent = `#${payment.id}`;
            document.getElementById('payment-date').textContent = formatDate(payment.payment_date);
            document.getElementById('payment-time').textContent = formatTime(payment.payment_time);
            
            // Set membership details
            document.getElementById('plan-type').textContent = payment.plan_type === 'vip' ? 'VIP' : payment.plan_type.charAt(0).toUpperCase() + payment.plan_type.slice(1);
            document.getElementById('plan-duration').textContent = payment.duration_type.charAt(0).toUpperCase() + payment.duration_type.slice(1);
            document.getElementById('membership-start').textContent = formatDate(payment.membership_start_date);
            document.getElementById('purchase-date').textContent = formatDate(payment.payment_date);
            
            // Set amounts
            document.getElementById('original-amount').textContent = `₱${parseFloat(payment.amount).toFixed(2)}`;
            document.getElementById('discount-amount').textContent = payment.discount_amount ? `₱${parseFloat(payment.discount_amount).toFixed(2)}` : '₱0.00';
            document.getElementById('total-amount').textContent = `₱${(parseFloat(payment.amount) - (parseFloat(payment.discount_amount) || 0)).toFixed(2)}`;
        }

        function closeReceiptModal() {
            document.getElementById('receiptPreviewModal').classList.add('hidden');
        }

        function selectPlan(planType) {
            // Remove previous selection
            document.querySelectorAll('.border-blue-500').forEach(el => {
                el.classList.remove('border-blue-500', 'bg-blue-50');
                el.classList.add('border-gray-200');
            });
            
            // Add selection to clicked plan
            const planElement = document.querySelector(`[onclick="selectPlan('${planType}')"]`);
            if (planElement) {
                planElement.classList.add('border-blue-500', 'bg-blue-50');
                planElement.classList.remove('border-gray-200');
            }
            
            // Update plan details
            const planPrices = {
                'basic': 1500,
                'premium': 2500,
                'vip': 3500
            };
            
            document.getElementById('plan-type').textContent = planType.charAt(0).toUpperCase() + planType.slice(1);
            document.getElementById('original-amount').textContent = `₱${planPrices[planType].toLocaleString()}.00`;
            updateTotalAmount();
        }

        function updateTotalAmount() {
            const originalAmount = parseFloat(document.getElementById('original-amount').textContent.replace('₱', '').replace(',', ''));
            const discountAmount = parseFloat(document.getElementById('discount-amount').textContent.replace('₱', '').replace(',', ''));
            const total = originalAmount - discountAmount;
            document.getElementById('total-amount').textContent = `₱${total.toLocaleString()}.00`;
        }

        function confirmPayment() {
            if (confirm('Are you sure you want to confirm this payment and activate the membership?')) {
                // Here you would typically submit the payment
                alert('Payment confirmed! Membership activated.');
                closeReceiptModal();
                window.location.reload();
            }
        }

        // Close modal when clicking outside
        document.getElementById('receiptPreviewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReceiptModal();
            }
        });

        // Preview CSV Function
        function previewCsv() {
            // Create modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg p-6 max-w-6xl max-h-[90vh] overflow-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">CSV Export Preview</h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="csvPreview" class="border rounded p-4 bg-gray-50">
                        <div class="text-center">Loading CSV data...</div>
                    </div>
                    <div class="flex justify-end space-x-4 mt-4">
                        <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Close</button>
                        <a href="{{ route('membership.payments.export_csv', request()->query()) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Download CSV</a>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            // Load CSV preview data
            const payments = @json($payments);
            let csvContent = 'Payment ID,Member,Plan & Duration,Amount,Payment Date,Membership Period,TIN,PWD,Senior Citizen,Discount Amount,Notes\n';
            
            payments.forEach(payment => {
                const memberName = payment.member ? `${payment.member.first_name} ${payment.member.last_name}` : 'N/A';
                const planDuration = `${payment.plan_type.toUpperCase()} - ${payment.duration_type}`;
                const amount = `₱${parseFloat(payment.amount).toFixed(2)}`;
                const paymentDate = new Date(payment.payment_date).toLocaleDateString();
                const membershipPeriod = `${new Date(payment.membership_start_date).toLocaleDateString()} to ${new Date(payment.membership_expiration_date).toLocaleDateString()}`;
                const tin = payment.tin || 'N/A';
                const isPwd = payment.is_pwd ? 'Yes' : 'No';
                const isSenior = payment.is_senior_citizen ? 'Yes' : 'No';
                const discountAmount = payment.discount_amount ? `₱${parseFloat(payment.discount_amount).toFixed(2)}` : '₱0.00';
                const notes = (payment.notes || '').replace(/,/g, ';');
                
                csvContent += `${payment.id},"${memberName}","${planDuration}","${amount}","${paymentDate}","${membershipPeriod}","${tin}","${isPwd}","${isSenior}","${discountAmount}","${notes}"\n`;
            });

            // Display as table
            const lines = csvContent.split('\n');
            const headers = lines[0].split(',');
            let tableHtml = '<div class="overflow-x-auto"><table class="min-w-full border-collapse border border-gray-300">';
            
            // Header row
            tableHtml += '<thead><tr class="bg-gray-100">';
            headers.forEach(header => {
                tableHtml += `<th class="border border-gray-300 px-4 py-2 text-left">${header.replace(/"/g, '')}</th>`;
            });
            tableHtml += '</tr></thead><tbody>';
            
            // Data rows
            for (let i = 1; i < lines.length - 1; i++) {
                const cells = lines[i].split(',');
                tableHtml += '<tr>';
                cells.forEach((cell, index) => {
                    const cellContent = cell.replace(/"/g, '');
                    if (index === 2 || index === 3 || index === 6 || index === 8) { // Amount, Discount columns
                        tableHtml += `<td class="border border-gray-300 px-4 py-2 text-right">${cellContent}</td>`;
                    } else {
                        tableHtml += `<td class="border border-gray-300 px-4 py-2">${cellContent}</td>`;
                    }
                });
                tableHtml += '</tr>';
            }
            
            tableHtml += '</tbody></table></div>';
            document.getElementById('csvPreview').innerHTML = tableHtml;
        }
    </script>
</x-layout>
