<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Payment Information -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h4>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Payment ID:</span>
                <span class="text-sm text-gray-900">#{{ $payment->id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Amount:</span>
                <span class="text-sm text-gray-900 font-semibold">₱{{ number_format($payment->amount, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Plan Type:</span>
                <span class="text-sm text-gray-900">{{ ucfirst($payment->plan_type) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Status:</span>
                <span class="px-2 py-1 text-xs font-semibold rounded-full
                    @if($payment->status === 'completed') bg-green-100 text-green-800
                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Payment Date:</span>
                <span class="text-sm text-gray-900">{{ $payment->created_at->format('M d, Y H:i') }}</span>
            </div>
            @if($payment->payment_method)
            <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Payment Method:</span>
                <span class="text-sm text-gray-900">{{ ucfirst($payment->payment_method) }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Member Information -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Member Information</h4>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Name:</span>
                <span class="text-sm text-gray-900">{{ $payment->member->first_name ?? 'N/A' }} {{ $payment->member->last_name ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Email:</span>
                <span class="text-sm text-gray-900">{{ $payment->member->email ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Mobile:</span>
                <span class="text-sm text-gray-900">{{ $payment->member->mobile_number ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm font-medium text-gray-500">Member ID:</span>
                <span class="text-sm text-gray-900">{{ $payment->member->uid ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Membership Period Information -->
@if($payment->member && $payment->member->currentMembershipPeriod)
<div class="mt-6 bg-gray-50 rounded-lg p-6">
    <h4 class="text-lg font-semibold text-gray-900 mb-4">Membership Period</h4>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="flex justify-between">
            <span class="text-sm font-medium text-gray-500">Start Date:</span>
            <span class="text-sm text-gray-900">{{ $payment->member->currentMembershipPeriod->start_date ? \Carbon\Carbon::parse($payment->member->currentMembershipPeriod->start_date)->format('M d, Y') : 'N/A' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm font-medium text-gray-500">End Date:</span>
            <span class="text-sm text-gray-900">{{ $payment->member->currentMembershipPeriod->end_date ? \Carbon\Carbon::parse($payment->member->currentMembershipPeriod->end_date)->format('M d, Y') : 'N/A' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm font-medium text-gray-500">Status:</span>
            <span class="px-2 py-1 text-xs font-semibold rounded-full
                @if($payment->member->currentMembershipPeriod->is_active) bg-green-100 text-green-800
                @else bg-red-100 text-red-800
                @endif">
                {{ $payment->member->currentMembershipPeriod->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>
</div>
@endif
