<x-layout>
    <x-nav-employee></x-nav-employee>
    <div class="flex-1 bg-white">
        <x-topbar>Member Details</x-topbar>

        <!-- Main Content -->
        <div class="p-6">
            <!-- Header with Back Button -->
            <div class="mb-6 sticky top-20 z-10 -mx-6 px-6 py-3 bg-white/90 backdrop-blur border-b border-gray-200">
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('employee.members') }}" class="flex items-center gap-2 text-black hover:text-blue-600 transition-colors duration-200">
                        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="font-medium">Back to Members</span>
                    </a>
                    <h2 class="text-xl md:text-2xl font-bold text-black">Member Details</h2>
                </div>
            </div>

            <!-- Member Details Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Full Name</label>
                                <p class="text-gray-900">{{ $member->full_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email</label>
                                <p class="text-gray-900">{{ $member->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Mobile Number</label>
                                <p class="text-gray-900">{{ $member->mobile_number }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Member Number</label>
                                <p class="text-gray-900">{{ $member->member_number }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">UID</label>
                                <p class="text-gray-900 font-mono">{{ $member->uid }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Membership Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Membership Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status</label>
                                <p class="text-gray-900">{{ ucfirst($member->status) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Subscription Status</label>
                                <p class="text-gray-900">{{ ucfirst($member->subscription_status) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Role</label>
                                <p class="text-gray-900">{{ ucfirst($member->role) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Created At</label>
                                <p class="text-gray-900">{{ $member->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('employee.members.edit', $member->id) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Member
                        </a>
                        <a href="{{ route('employee.members.profile', $member->id) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            View Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
