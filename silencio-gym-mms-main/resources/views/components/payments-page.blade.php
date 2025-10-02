@props(['isEmployee' => false, 'payments' => null, 'completedCount' => 0, 'totalRevenue' => 0])
@php
    if (!isset($payments) || !$payments) {
        $payments = collect();
    }
@endphp


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
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #000000;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-medium" style="color: #059669;">Completed</p>
                                    <p class="text-2xl sm:text-3xl font-bold" style="color: #000000;">{{ $completedCount }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow" style="border-color: #1E40AF; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div class="flex items-center">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full flex items-center justify-center mr-3 sm:mr-4" style="background-color: #1E40AF;">
                                    <span class="text-xl sm:text-2xl font-bold" style="color: #FFFFFF;">₱</span>
                                </div>
                                <div>
                                    <p class="text-xs sm:text-sm font-medium" style="color: #1E40AF;">Total Revenue</p>
                                    <p class="text-2xl sm:text-3xl font-bold" style="color: #000000;">₱{{ number_format($totalRevenue, 2) }}</p>
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
                    <table class="payments-table w-full" style="min-width: 1400px;">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 200px; min-width: 200px;">MEMBER</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 150px; min-width: 150px;">PLAN & DURATION</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 120px; min-width: 120px;">AMOUNT</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 150px; min-width: 150px;">PAYMENT DATE</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px; min-width: 100px;">STATUS</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 200px; min-width: 200px;">MEMBERSHIP PERIOD</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px; min-width: 100px;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <!-- MEMBER -->
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background-color: #E3F2FD;">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $payment->member->first_name ?? 'N/A' }} {{ $payment->member->last_name ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $payment->member->email ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- PLAN & DURATION -->
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>
                                            <span class="font-semibold">{{ ucfirst($payment->plan_type) }}</span>
                                            <span class="text-gray-400 mx-1">+</span>
                                            <span class="font-semibold">{{ ucfirst($payment->duration_type) }}</span>
                                        </div>
                                    </td>
                                    
                                    <!-- AMOUNT -->
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="font-bold">₱{{ number_format($payment->amount, 2) }}</div>
                                    </td>
                                    
                                    <!-- PAYMENT DATE -->
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->payment_time ? \Carbon\Carbon::parse($payment->payment_time)->format('h:i:s A') : 'N/A' }}</div>
                                    </td>
                                    
                                    <!-- STATUS -->
                                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    
                                    <!-- MEMBERSHIP PERIOD -->
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($payment->membership_start_date && $payment->membership_expiration_date)
                                            <div>{{ \Carbon\Carbon::parse($payment->membership_start_date)->format('M d, Y') }} – {{ \Carbon\Carbon::parse($payment->membership_expiration_date)->format('M d, Y') }}</div>
                                            @php
                                                $expirationDate = \Carbon\Carbon::parse($payment->membership_expiration_date);
                                                $now = \Carbon\Carbon::now();
                                                $daysLeft = $expirationDate->diffInDays($now);
                                                
                                                // Ensure positive whole number
                                                if ($daysLeft < 0) {
                                                    $daysLeft = abs($daysLeft);
                                                }
                                                $daysLeft = (int) $daysLeft;
                                            @endphp
                                            <div class="text-sm text-green-600">{{ $daysLeft }} days left</div>
                                        @else
                                            <div class="text-gray-500">N/A</div>
                                        @endif
                                    </td>
                                    
                                    <!-- ACTIONS -->
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <button onclick="viewPaymentDetails({{ $payment->id }})" class="text-blue-600 hover:text-blue-900 font-medium" title="View Details">View</button>
                                            <a href="{{ $isEmployee ? route('employee.membership.payments.print', $payment->id) : route('membership.payments.print', $payment->id) }}" target="_blank" class="text-blue-600 hover:text-blue-900 font-medium" title="Print Receipt">Print</a>
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
                @if(method_exists($payments, 'hasPages') && $payments->hasPages())
                    <div class="pagination-container bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>

            <!-- Payment Details Modal -->
            <div id="paymentDetailsModal" class="fixed inset-0 flex items-center justify-center p-2 sm:p-4 hidden z-50">
                <div class="bg-white rounded-xl shadow-lg max-w-6xl w-full transform transition-all duration-300 scale-95 opacity-0 border border-gray-200" 
                     id="paymentDetailsModalContent"
                     style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); max-height: 90vh; overflow-y: auto;">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-4 sm:mb-6">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Payment Details</h3>
                            <button onclick="closePaymentDetailsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
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
    min-width: 1400px;
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

@media (max-width: 1600px) {
    .payments-table {
        min-width: 1200px;
    }
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
                
                // Show modal with animation
                const modal = document.getElementById('paymentDetailsModal');
                const content = document.getElementById('paymentDetailsModalContent');
                
                modal.classList.remove('hidden');
                
                // Trigger animation
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
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
    const modal = document.getElementById('paymentDetailsModal');
    const content = document.getElementById('paymentDetailsModalContent');
    
    // Trigger close animation
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Close modal when clicking outside
document.getElementById('paymentDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePaymentDetailsModal();
    }
});
</script>
