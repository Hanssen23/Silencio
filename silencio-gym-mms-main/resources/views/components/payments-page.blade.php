@props(['isEmployee' => false])

<x-layout>
    @if($isEmployee)
        <x-nav-employee></x-nav-employee>
    @else
        <x-nav></x-nav>
    @endif
    <div class="flex-1 bg-white">
        <x-topbar>All Payments</x-topbar>

        <div class="bg-white min-h-screen p-4 sm:p-6 stable-layout resize-handler">
            <!-- Header with Quick Actions -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-lg shadow-sm border p-4 sm:p-6 lg:p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 sm:mb-8 gap-4">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-bold" style="color: #1E40AF;">Payment Records</h2>
                            <p class="text-base sm:text-lg mt-2" style="color: #6B7280;">View and manage all payment transactions</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 header-actions">
                            <a href="{{ $isEmployee ? route('employee.membership.manage-member') : route('membership.manage-member') }}" class="inline-flex items-center justify-center px-4 sm:px-6 py-3 text-white rounded-lg transition-colors shadow-sm min-h-[44px] w-full sm:w-auto" style="background-color: #2563EB;" onmouseover="this.style.backgroundColor='#1D4ED8'" onmouseout="this.style.backgroundColor='#2563EB'">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="text-sm sm:text-base">New Payment</span>
                            </a>
                            <a href="{{ $isEmployee ? route('employee.membership-plans') : route('membership.plans.index') }}" class="inline-flex items-center justify-center px-4 sm:px-6 py-3 text-white rounded-lg transition-colors shadow-sm min-h-[44px] w-full sm:w-auto" style="background-color: #6B7280;" onmouseover="this.style.backgroundColor='#4B5563'" onmouseout="this.style.backgroundColor='#6B7280'">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm sm:text-base">Manage Plans</span>
                            </a>
                            <a href="{{ $isEmployee ? route('employee.membership.payments.export_csv', request()->query()) : route('membership.payments.export_csv', request()->query()) }}" class="inline-flex items-center justify-center px-4 sm:px-6 py-3 text-white rounded-lg transition-colors shadow-sm min-h-[44px] w-full sm:w-auto" style="background-color: #6B7280;" onmouseover="this.style.backgroundColor='#4B5563'" onmouseout="this.style.backgroundColor='#6B7280'">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm sm:text-base">Download CSV</span>
                            </a>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 summary-cards">
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
                        <div class="bg-white border rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow" style="border-color: #DC2626; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div class="flex items-center">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center mr-3 sm:mr-4" style="background-color: #DC2626;">
                                    <span class="text-xl sm:text-2xl">❌</span>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-medium" style="color: #DC2626;">Pending</p>
                                    <p class="text-2xl sm:text-3xl font-bold" style="color: #000000;">{{ $payments->where('status', 'pending')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-sm border p-4 sm:p-6 mb-6" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <form method="GET" action="{{ $isEmployee ? route('employee.membership.payments') : route('membership.payments.index') }}" id="filterForm" class="w-full">
                    <div class="filter-section grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search by member name, payment ID..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="plan_type" class="block text-sm font-medium text-gray-700 mb-2">Plan Type</label>
                            <select id="plan_type" name="plan_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Plans</option>
                                <option value="basic" {{ request('plan_type') == 'basic' ? 'selected' : '' }}>Basic</option>
                                <option value="premium" {{ request('plan_type') == 'premium' ? 'selected' : '' }}>Premium</option>
                                <option value="vip" {{ request('plan_type') == 'vip' ? 'selected' : '' }}>VIP</option>
                            </select>
                        </div>
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" id="date" name="date" value="{{ request('date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Status</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 mt-4">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            Apply Filters
                        </button>
                        <a href="{{ $isEmployee ? route('employee.membership.payments') : route('membership.payments.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-center">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Payments Table -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="payments-table-container overflow-x-auto">
                    <table class="payments-table w-full" style="min-width: 1200px;">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 120px; min-width: 120px;">Payment ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 150px; min-width: 150px;">Member</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px; min-width: 100px;">Plan Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px; min-width: 100px;">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 120px; min-width: 120px;">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px; min-width: 100px;">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px; min-width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        #{{ $payment->id }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $payment->member->first_name ?? 'N/A' }} {{ $payment->member->last_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($payment->plan_type === 'vip') bg-purple-100 text-purple-800
                                            @elseif($payment->plan_type === 'premium') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($payment->plan_type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        ₱{{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $payment->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($payment->status === 'completed') bg-green-100 text-green-800
                                            @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <div class="action-buttons flex flex-row gap-2 items-center justify-start" style="min-width: 80px;">
                                            <button onclick="viewPaymentDetails({{ $payment->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="View Details" aria-label="View payment details">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <a href="{{ $isEmployee ? route('employee.membership.payments.print', $payment->id) : route('membership.payments.print', $payment->id) }}" class="p-2 text-gray-600 hover:bg-gray-50 rounded-md transition-colors" title="Print Receipt" aria-label="Print payment receipt">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        No payments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($payments->hasPages())
                    <div class="pagination-container bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>

            <!-- Payment Details Modal -->
            <div id="paymentDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Payment Details</h3>
                            <button onclick="closePaymentDetailsModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="paymentDetailsContent" class="space-y-4">
                            <!-- Payment details will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>

<style>
.payments-container {
    position: relative;
    width: 100%;
    min-height: 100vh;
}

.resize-handler {
    transition: all 0.3s ease;
}

.stable-layout {
    will-change: auto;
}

.header-actions {
    flex-wrap: wrap;
    gap: 0.5rem;
}

.summary-cards {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}

.filter-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1rem;
}

.payments-table-container {
    overflow-x: auto;
    min-height: 400px;
}

.payments-table {
    table-layout: fixed;
    width: 100%;
    min-width: 1200px;
}

.action-buttons {
    display: flex;
    flex-direction: row;
    gap: 0.5rem;
    align-items: center;
    justify-content: flex-start;
    min-width: 80px;
}

.action-buttons a,
.action-buttons button {
    min-width: 32px;
    min-height: 32px;
    padding: 0.5rem;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.action-buttons a:hover,
.action-buttons button:hover {
    background-color: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

@media (max-width: 1400px) {
    .payments-table {
        min-width: 1000px;
    }
}

@media (max-width: 1200px) {
    .payments-table {
        min-width: 800px;
    }
}

@media (max-width: 1024px) {
    .payments-table {
        min-width: 700px;
    }
}

@media (max-width: 768px) {
    .payments-table {
        min-width: 600px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
}

@media (max-width: 640px) {
    .payments-table {
        min-width: 500px;
    }
}

@media (min-resolution: 1.25dppx) {
    .payments-table th,
    .payments-table td {
        font-size: 0.875rem;
        padding: 0.5rem;
    }
}

@media (min-resolution: 1.5dppx) {
    .payments-table th,
    .payments-table td {
        font-size: 0.8rem;
        padding: 0.375rem;
    }
}

@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .payments-table th,
    .payments-table td {
        font-size: 0.8rem;
        padding: 0.375rem;
    }
}

@media print {
    .action-buttons {
        display: none !important;
    }
    
    .payments-table-container {
        overflow: visible !important;
    }
}
</style>

<script>
// Window resize handler with debouncing
let resizeTimeout;
function handleResize() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        // Force reflow to recalculate layout
        document.body.style.display = 'none';
        document.body.offsetHeight; // Trigger reflow
        document.body.style.display = '';
    }, 100);
}

// Zoom level detection
let lastZoomLevel = window.devicePixelRatio;
function checkZoomLevel() {
    if (window.devicePixelRatio !== lastZoomLevel) {
        lastZoomLevel = window.devicePixelRatio;
        // Force layout recalculation on zoom change
        handleResize();
    }
}

// Event listeners
window.addEventListener('resize', handleResize);
window.addEventListener('orientationchange', handleResize);

// Check zoom level periodically
setInterval(checkZoomLevel, 500);

// Set initial table width on page load
document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.payments-table');
    if (table) {
        table.style.width = '100%';
    }
});

// Payment details modal functions
function viewPaymentDetails(paymentId) {
    const isEmployee = window.location.pathname.includes('/employee');
    const url = isEmployee ? `/employee/membership/payments/${paymentId}/details` : `/membership/payments/${paymentId}/details`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('paymentDetailsContent').innerHTML = data.html;
                document.getElementById('paymentDetailsModal').classList.remove('hidden');
            } else {
                alert('Error loading payment details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading payment details');
        });
}

function closePaymentDetailsModal() {
    document.getElementById('paymentDetailsModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('paymentDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePaymentDetailsModal();
    }
});
</script>
