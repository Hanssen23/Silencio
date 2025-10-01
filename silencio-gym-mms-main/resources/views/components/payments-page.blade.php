@props(['payments'])

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
</div>
