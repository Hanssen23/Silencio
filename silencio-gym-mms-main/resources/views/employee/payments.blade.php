<x-layout>
    <x-nav-employee></x-nav-employee>
    <div class="flex-1 bg-white">
        <x-topbar>All Payments</x-topbar>

        <div class="bg-white min-h-screen p-6">
            <!-- Header with Quick Actions -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-sm border p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-3xl font-bold" style="color: #1E40AF;">Payment Records</h2>
                            <p class="text-lg mt-2" style="color: #6B7280;">View and manage all payment transactions</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{ route('employee.membership.manage-member') }}" class="inline-flex items-center px-6 py-3 text-white rounded-lg transition-colors shadow-sm" style="background-color: #2563EB;" onmouseover="this.style.backgroundColor='#1D4ED8'" onmouseout="this.style.backgroundColor='#2563EB'">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                New Payment
                            </a>
                            <a href="{{ route('employee.membership.plans.index') }}" class="inline-flex items-center px-6 py-3 text-white rounded-lg transition-colors shadow-sm" style="background-color: #6B7280;" onmouseover="this.style.backgroundColor='#4B5563'" onmouseout="this.style.backgroundColor='#6B7280'">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Manage Plans
                            </a>
                            <a href="{{ route('employee.membership.payments.export_csv', request()->query()) }}" class="inline-flex items-center px-6 py-3 text-white rounded-lg transition-colors shadow-sm" style="background-color: #059669;" onmouseover="this.style.backgroundColor='#047857'" onmouseout="this.style.backgroundColor='#059669'">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export CSV
                            </a>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white border rounded-lg p-6 hover:shadow-lg transition-shadow" style="border-color: #059669; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div class="flex items-center">
                                <div class="w-14 h-14 rounded-full flex items-center justify-center mr-4" style="background-color: #059669;">
                                    <span class="text-2xl">✅</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium" style="color: #059669;">Completed</p>
                                    <p class="text-3xl font-bold" style="color: #000000;">{{ $completedCount }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-6 hover:shadow-lg transition-shadow" style="border-color: #059669; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div class="flex items-center">
                                <div class="w-14 h-14 rounded-full flex items-center justify-center mr-4" style="background-color: #059669;">
                                    <span class="text-2xl font-bold" style="color: #FFFFFF;">₱</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium" style="color: #059669;">Total Revenue</p>
                                    <p class="text-3xl font-bold" style="color: #000000;">₱{{ number_format($totalRevenue, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Printing Features Info -->
                    <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-blue-900 mb-2">Payment Printing Features</h3>
                                <div class="text-blue-800 space-y-2">
                                    <p class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                        <strong>Print Receipts:</strong> Click the "Print" button in the actions column or use the "Print Receipt" button in payment details to generate professional receipts
                                    </p>
                                    <p class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <strong>Export to CSV:</strong> Use the "Export CSV" button to download all payment data with current filters applied
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-sm border p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <h3 class="text-xl font-semibold mb-6" style="color: #1E40AF;">Filters & Search</h3>
                    <form method="GET" action="{{ route('employee.membership.payments') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-3" style="color: #374151;">Plan Type</label>
                            <select name="plan_type" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" style="border-color: #E5E7EB;">
                                <option value="">All Plans</option>
                                <option value="basic" {{ request('plan_type') === 'basic' ? 'selected' : '' }}>Basic</option>
                                <option value="vip" {{ request('plan_type') === 'vip' ? 'selected' : '' }}>VIP</option>
                                <option value="premium" {{ request('plan_type') === 'premium' ? 'selected' : '' }}>Premium</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-3" style="color: #374151;">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" style="border-color: #E5E7EB;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-3" style="color: #374151;">Search</label>
                            <div class="flex space-x-3">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by member name, email..." class="flex-1 px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" style="border-color: #E5E7EB;">
                                <button type="submit" class="px-6 py-3 text-white rounded-lg transition-colors shadow-sm" style="background-color: #2563EB;" onmouseover="this.style.backgroundColor='#1D4ED8'" onmouseout="this.style.backgroundColor='#2563EB'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Filter Actions -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="submit" form="filterForm" class="px-6 py-2 text-white rounded-lg transition-colors" style="background-color: #2563EB;" onmouseover="this.style.backgroundColor='#1D4ED8'" onmouseout="this.style.backgroundColor='#2563EB'">
                            Apply
                        </button>
                        @if(request('plan_type') || request('date') || request('search'))
                        <a href="{{ route('employee.membership.payments') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors" style="border-color: #E5E7EB;">
                            Reset
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y" style="border-color: #E5E7EB;">
                        <thead style="background-color: #1E40AF;">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Member</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Plan</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-white uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="background-color: #FFFFFF; border-color: #E5E7EB;">
                            @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors" style="background-color: {{ $loop->even ? '#F9FAFB' : '#FFFFFF' }};">
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #1E40AF;">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold" style="color: #000000;">{{ $payment->member->full_name }}</div>
                                            <div class="text-sm" style="color: #6B7280;">{{ $payment->member->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="text-sm" style="color: #000000;">
                                        <span class="font-semibold">{{ $payment->plan_type === 'vip' ? 'VIP' : ucfirst($payment->plan_type) }}</span>
                                        <span style="color: #6B7280;" class="mx-2">+</span>
                                        <span class="font-semibold">{{ ucfirst($payment->duration_type) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap text-right">
                                    <div class="text-lg font-bold" style="color: #000000;">₱{{ number_format($payment->amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap">
                                    <div class="text-sm" style="color: #000000;">{{ $payment->full_payment_date ? $payment->full_payment_date->format('M d, Y') : 'N/A' }}</div>
                                    <div class="text-sm" style="color: #6B7280;">{{ $payment->full_payment_date ? $payment->full_payment_date->format('h:i:s A') : 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-6 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <button onclick="viewPaymentDetails({{ $payment->id }})" class="text-blue-600 hover:text-blue-900 font-medium" title="View Details">👁️ View</button>
                                        <a href="{{ route('employee.membership.payments.print', $payment->id) }}" target="_blank" class="text-purple-600 hover:text-purple-900 font-medium" title="Print Receipt">🖨️ Print</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center" style="color: #6B7280;">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 mb-6" style="color: #6B7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-xl font-semibold mb-3" style="color: #000000;">No payments found</p>
                                        <p class="text-lg mb-6" style="color: #6B7280;">Get started by processing your first payment</p>
                                        <a href="{{ route('employee.membership.manage-member') }}" class="inline-flex items-center px-8 py-4 text-white rounded-lg transition-colors shadow-sm" style="background-color: #2563EB;" onmouseover="this.style.backgroundColor='#1D4ED8'" onmouseout="this.style.backgroundColor='#2563EB'">
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
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-sm border px-8 py-6" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    {{ $payments->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Payment Details Side Panel -->
    <div id="paymentDetailsModal" class="fixed inset-0 flex items-center justify-center p-4 hidden z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto border border-gray-200" style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
            <div class="flex items-center justify-between p-6 border-b border-gray-200" style="background-color: #1E40AF;">
                <h3 class="text-xl font-bold text-white">Payment Details</h3>
                <button onclick="closePaymentDetailsModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="paymentDetailsContent" class="p-6">
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
            fetch(`/employee/membership/payments/${paymentId}/details`)
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
                        <a href="/employee/membership/payments/${payment.id}/print" target="_blank" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center">
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
    </script>
</x-layout>