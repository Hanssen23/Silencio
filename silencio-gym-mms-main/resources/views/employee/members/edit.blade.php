<x-layout>
    <x-nav-employee></x-nav-employee>
    <div class="flex-1 bg-white">
        <x-topbar>Members</x-topbar>

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
                <h2 class="text-2xl font-bold mt-2" style="color: #1E40AF;">Edit Member: {{ $member->full_name }}</h2>
            </div>

            <!-- Edit Member Form -->
            <div class="bg-white rounded-lg border p-6" style="border-color: #E5E7EB; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-lg" style="background-color: #FEF2F2; border-color: #FECACA;">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5" style="color: #DC2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium" style="color: #DC2626;">There were errors with your submission</h3>
                                <div class="mt-2 text-sm" style="color: #DC2626;">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <form method="POST" action="{{ route('employee.members.update', $member->id) }}" class="space-y-8">
                    @method('PUT')
                    @csrf
                    
                    <!-- Personal Information Section -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4" style="color: #1E40AF;">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name Field -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium mb-2" style="color: #6B7280;">First Name</label>
                                <input type="text" id="first_name" name="first_name" required 
                                       class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       style="border-color: #E5E7EB;"
                                       placeholder="Enter first name"
                                       value="{{ old('first_name', $member->first_name) }}">
                            </div>

                            <!-- Last Name Field -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium mb-2" style="color: #6B7280;">Last Name</label>
                                <input type="text" id="last_name" name="last_name" required 
                                       class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       style="border-color: #E5E7EB;"
                                       placeholder="Enter last name"
                                       value="{{ old('last_name', $member->last_name) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4" style="color: #1E40AF;">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-sm font-medium mb-2" style="color: #6B7280;">
                                    Email
                                </label>
                                <input type="email" id="email" name="email" readonly
                                       class="w-full px-4 py-3 border rounded-lg bg-gray-100 cursor-not-allowed" 
                                       style="border-color: #E5E7EB; color: #6B7280;"
                                       placeholder="Email cannot be edited"
                                       value="{{ old('email', $member->email) }}">
                                <p class="text-xs mt-1" style="color: #6B7280;">Email cannot be modified for security reasons</p>
                            </div>

                            <!-- Mobile Number Field -->
                            <div>
                                <label for="mobile_number" class="block text-sm font-medium mb-2" style="color: #6B7280;">
                                    Mobile Number
                                </label>
                                <input type="tel" id="mobile_number" name="mobile_number" readonly
                                       class="w-full px-4 py-3 border rounded-lg bg-gray-100 cursor-not-allowed" 
                                       style="border-color: #E5E7EB; color: #6B7280;"
                                       placeholder="Phone number cannot be edited"
                                       value="{{ old('mobile_number', $member->mobile_number) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Membership Information Section -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4" style="color: #1E40AF;">Membership Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Member Number Field (Read-only for employees) -->
                            <div>
                                <label for="member_number" class="block text-sm font-medium mb-2" style="color: #6B7280;">Member Number</label>
                                <input type="text" id="member_number" name="member_number" readonly
                                       class="w-full px-4 py-3 border rounded-lg bg-gray-100 cursor-not-allowed" 
                                       style="border-color: #E5E7EB; color: #6B7280;"
                                       placeholder="Member number cannot be edited"
                                       value="{{ old('member_number', $member->member_number) }}">
                                <p class="text-xs mt-1" style="color: #6B7280;">Member number cannot be modified</p>
                            </div>

                            <!-- UID Field (Read-only for employees) -->
                            <div>
                                <label for="uid" class="block text-sm font-medium mb-2" style="color: #6B7280;">UID</label>
                                <input type="text" id="uid" name="uid" readonly
                                       class="w-full px-4 py-3 border rounded-lg bg-gray-100 cursor-not-allowed font-mono" 
                                       style="border-color: #E5E7EB; color: #6B7280;"
                                       placeholder="UID cannot be edited"
                                       value="{{ old('uid', $member->uid) }}">
                                <p class="text-xs mt-1" style="color: #6B7280;">UID cannot be modified</p>
                            </div>

                            <!-- Membership Status Display -->
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: #6B7280;">Membership Status</label>
                                <div class="w-full px-4 py-3 border rounded-lg bg-gray-100 cursor-not-allowed" 
                                     style="border-color: #E5E7EB; color: #6B7280;">
                                    @if($member->membership_status === 'Expired')
                                        {{ ucfirst($member->membership ?? 'No Plan') }} 
                                        <span class="text-red-600">(Expired)</span>
                                    @elseif($member->membership_status === 'Expiring Soon')
                                        {{ ucfirst($member->membership ?? 'No Plan') }} 
                                        <span class="text-yellow-600">(Expiring Soon)</span>
                                    @elseif($member->membership_status === 'Active')
                                        {{ ucfirst($member->membership ?? 'No Plan') }} 
                                        <span class="text-green-600">(Active)</span>
                                    @else
                                        <span class="text-gray-500">No Active Membership</span>
                                    @endif
                                </div>
                                <p class="text-xs mt-1" style="color: #6B7280;">Membership type is automatically set when payment is processed</p>
                            </div>
                        </div>
                    </div>

                    <!-- Plan Information Section -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4" style="color: #1E40AF;">Plan Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Current Plan -->
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: #6B7280;">Current Plan</label>
                                <div class="w-full px-4 py-3 border rounded-lg bg-gray-100 cursor-not-allowed" 
                                     style="border-color: #E5E7EB; color: #6B7280;">
                                    @if($member->current_plan_type)
                                        {{ ucfirst($member->current_plan_type) }}
                                        @if($member->current_duration_type)
                                            ({{ ucfirst($member->current_duration_type) }})
                                        @endif
                                    @else
                                        <span class="text-gray-500">No Plan Assigned</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Membership Expiration -->
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: #6B7280;">Membership Expiration</label>
                                <div class="w-full px-4 py-3 border rounded-lg bg-gray-100 cursor-not-allowed" 
                                     style="border-color: #E5E7EB; color: #6B7280;">
                                    @if($member->membership_expires_at)
                                        {{ $member->membership_expires_at->format('M d, Y') }}
                                        @if($member->is_expired)
                                            <span class="text-red-600 ml-2">(Expired)</span>
                                        @elseif($member->days_until_expiration <= 7)
                                            <span class="text-yellow-600 ml-2">(Expires in {{ $member->days_until_expiration }} days)</span>
                                        @else
                                            <span class="text-green-600 ml-2">({{ $member->days_until_expiration }} days remaining)</span>
                                        @endif
                                    @else
                                        <span class="text-gray-500">No Expiration Date</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit and Cancel Buttons -->
                    <div class="flex items-center gap-4 pt-8">
                        <button type="submit" 
                                class="px-6 py-3 text-white rounded-lg font-medium transition-colors duration-200 flex items-center gap-2" 
                                style="background-color: #2563EB;" 
                                onmouseover="this.style.backgroundColor='#1D4ED8'" 
                                onmouseout="this.style.backgroundColor='#2563EB'">
                            Save
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                        <a href="{{ route('employee.members') }}" 
                           class="px-6 py-3 border rounded-lg font-medium transition-colors duration-200" 
                           style="border-color: #E5E7EB; color: #6B7280;" 
                           onmouseover="this.style.backgroundColor='#F3F4F6'" 
                           onmouseout="this.style.backgroundColor='transparent'">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>