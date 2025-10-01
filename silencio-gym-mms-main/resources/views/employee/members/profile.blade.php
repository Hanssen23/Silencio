<x-layout>
    <x-nav-employee></x-nav-employee>
    <div class="flex-1 bg-white">
        <x-topbar>Member Profile</x-topbar>

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
                <h2 class="text-2xl font-bold mt-2" style="color: #1E40AF;">{{ $member->full_name }}'s Profile</h2>
            </div>

            <!-- Two-Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Basic Information Card -->
                    <div class="bg-white rounded-lg border p-6" style="border-color: #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: #1E40AF;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Basic Information
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium" style="color: #6B7280;">UID</label>
                                <p class="text-sm font-mono mt-1" style="color: #000000;">{{ $member->uid }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium" style="color: #6B7280;">Member Number</label>
                                <p class="text-sm mt-1" style="color: #000000;">{{ $member->member_number }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium" style="color: #6B7280;">Full Name</label>
                                <p class="text-sm mt-1" style="color: #000000;">{{ $member->full_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium flex items-center gap-2" style="color: #6B7280;">
                                    📧 Email
                                </label>
                                <p class="text-sm mt-1" style="color: #000000;">{{ $member->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium flex items-center gap-2" style="color: #6B7280;">
                                    📱 Mobile Number
                                </label>
                                <p class="text-sm mt-1" style="color: #000000;">{{ $member->mobile_number }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium" style="color: #6B7280;">Status</label>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1 {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Current Membership Card -->
                    <div class="bg-white rounded-lg border p-6" style="border-color: #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: #1E40AF;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Current Membership
                        </h3>
                        @if($currentMembership)
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium" style="color: #6B7280;">Plan Type</label>
                                    <p class="text-sm mt-1" style="color: #000000;">{{ ucfirst($currentMembership->plan_type) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium" style="color: #6B7280;">Duration</label>
                                    <p class="text-sm mt-1" style="color: #000000;">{{ ucfirst($currentMembership->duration_type) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium" style="color: #6B7280;">Start Date</label>
                                    <p class="text-sm mt-1" style="color: #000000;">{{ $currentMembership->start_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium" style="color: #6B7280;">Expiration Date</label>
                                    <p class="text-sm mt-1" style="color: #000000;">{{ $currentMembership->expiration_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium" style="color: #6B7280;">Status</label>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1 {{ $currentMembership->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $currentMembership->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl mb-4">📋</div>
                                <p class="text-gray-500">No active membership</p>
                                <p class="text-sm text-gray-400 mt-1">Member needs to purchase a plan</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Recent Attendance Card -->
                    <div class="bg-white rounded-lg border p-6" style="border-color: #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: #1E40AF;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Recent Attendance
                        </h3>
                        @if($attendances->count() > 0)
                            <div class="space-y-3">
                                @foreach($attendances->take(5) as $attendance)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="text-sm font-medium" style="color: #000000;">
                                                {{ $attendance->check_in_time->format('M d, Y') }}
                                            </p>
                                            <p class="text-xs" style="color: #6B7280;">
                                                {{ $attendance->check_in_time->format('H:i') }}
                                                @if($attendance->check_out_time)
                                                    - {{ $attendance->check_out_time->format('H:i') }}
                                                @else
                                                    - Still in gym
                                                @endif
                                            </p>
                                        </div>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $attendance->check_out_time ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $attendance->check_out_time ? 'Completed' : 'Active' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl mb-4">📅</div>
                                <p class="text-gray-500">No attendance records</p>
                            </div>
                        @endif
                    </div>

                    <!-- Recent Payments Card -->
                    <div class="bg-white rounded-lg border p-6" style="border-color: #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: #1E40AF;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Recent Payments
                        </h3>
                        @if($payments->count() > 0)
                            <div class="space-y-3">
                                @foreach($payments->take(5) as $payment)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="text-sm font-medium" style="color: #000000;">
                                                {{ $payment->plan_type }} - {{ $payment->duration_type }}
                                            </p>
                                            <p class="text-xs" style="color: #6B7280;">
                                                {{ $payment->payment_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold" style="color: #000000;">
                                                ₱{{ number_format($payment->amount, 2) }}
                                            </p>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-4xl mb-4">💳</div>
                                <p class="text-gray-500">No payment records</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layout>
