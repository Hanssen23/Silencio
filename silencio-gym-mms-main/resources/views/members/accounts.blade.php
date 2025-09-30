<x-layout>
    <link rel="stylesheet" href="{{ asset('css/profile-card.css') }}">
    <x-nav-member></x-nav-member>
    <div class="flex-1 bg-gray-100">
        <x-topbar>My Account</x-topbar>

        <div class="bg-gray-100 min-h-screen p-6">
            <!-- Editable Profile Section -->
            <div class="mb-8">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                     <div class="flex items-center justify-between mb-8">
                         <h2 class="text-3xl font-bold text-gray-900">My Profile</h2>
                     </div>

                    <!-- Editable Profile Card -->
                    <div class="profile-card">
                        <div class="flex items-center mb-6">
                            <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mr-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                <h3 class="text-2xl font-semibold text-gray-900">{{ $member->full_name }}</h3>
                                <p class="text-lg text-gray-600">Member #{{ $member->member_number }}</p>
                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($member->status) }} Member
                                </span>
                            </div>
                                </div>

                        <!-- Profile Edit Form -->
                        <form id="profileUpdateForm" method="POST" action="{{ route('member.profile.update') }}" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Email Field -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ $member->email }}" 
                                           readonly
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                           style="color: #6B7280;"
                                           required>
                                    <p class="text-xs text-gray-500 mt-1">Email cannot be modified for security reasons</p>
                                </div>

                                <!-- Mobile Number Field -->
                                <div>
                                    <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mobile Number
                                    </label>
                                    <input type="tel" 
                                           id="mobile_number" 
                                           name="mobile_number" 
                                           value="{{ $member->mobile_number }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                           placeholder="+63 XXX XXX XXXX">
                                    <p class="text-xs text-gray-500 mt-1">Include country code (e.g., +63 for Philippines)</p>
                                </div>
                            </div>

                            <!-- Read-only Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-200">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                                        {{ $member->full_name }}
                        </div>
                                    <p class="text-xs text-gray-500 mt-1">Contact admin to change your name</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Member Number</label>
                                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700">
                                        {{ $member->member_number }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Your unique member identifier</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end pt-6">
                                <button type="submit" 
                                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content Panels -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
                <!-- 1. Attendance & Activity Panel -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Attendance & Activity
                        </h3>
                    </div>

                <!-- Recent Attendance -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Recent Attendance</h4>
                        @if($recentAttendance->count() > 0)
                            <div class="overflow-hidden rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recentAttendance->take(5) as $index => $attendance)
                                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $attendance->check_in_time->format('M d, Y') }}</td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $attendance->check_in_time->format('g:i A') }}</td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                @if($attendance->check_out_time)
                                                    {{ $attendance->check_out_time->format('g:i A') }}
                                                @else
                                                    Still In
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-gray-500">No attendance records found</p>
                        </div>
                        @endif
                    </div>

                    <!-- Login History -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Recent Login History</h4>
                        @if($loginHistory->count() > 0)
                            <div class="space-y-2">
                                @foreach($loginHistory->take(5) as $login)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                        <span class="text-sm font-medium text-gray-900">{{ $login->check_in_time->format('M d, Y') }}</span>
                                        <span class="text-sm text-gray-500 ml-2">{{ $login->check_in_time->format('g:i A') }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $login->check_in_time->diffForHumans() }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 bg-gray-50 rounded-lg">
                                <p class="text-gray-500 text-sm">No login history available</p>
                        </div>
                    @endif
                    </div>
                </div>

                <!-- 2. Billing & Payments Panel -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                            Billing & Payments
                        </h3>
                                    </div>

                    <!-- Membership Status Summary -->
                    <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg border border-green-200">
                        <div class="flex items-center justify-between">
                                    <div>
                                <h4 class="text-lg font-semibold text-gray-900">Current Membership</h4>
                                <p class="text-sm text-gray-600">{{ ucfirst($currentPlan ?: 'No Active Plan') }} - {{ ucfirst($currentDuration ?: 'N/A') }}</p>
                                    </div>
                            <div class="text-right">
                                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $membershipStatus === 'Active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $membershipStatus }}
                                </span>
                                @if($expiresAt)
                                    <p class="text-xs text-gray-500 mt-1">Expires: {{ $expiresAt->format('M d, Y') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment History -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Payment History</h4>
                        @if($paymentHistory->count() > 0)
                            <div class="overflow-hidden rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($paymentHistory as $index => $payment)
                                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">₱{{ number_format($payment->amount, 2) }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($payment->payment_method ?? 'Cash') }}</td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                            @endforeach
                                    </tbody>
                                </table>
                        </div>
                    @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <p class="text-gray-500">No payment records found</p>
                            </div>
                    @endif
                    </div>

                </div>
            </div>

            <!-- 3. Membership History Timeline -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Membership History Timeline
                    </h3>
                </div>

                @if($membershipPeriods->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-purple-50 to-blue-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($membershipPeriods as $index => $period)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-blue-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                                            <span class="text-sm font-semibold text-gray-900">{{ ucfirst($period->plan_type) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst($period->duration_type) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $period->start_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $period->expiration_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $period->status === 'active' ? 'bg-green-100 text-green-800' : ($period->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($period->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No Membership History</h4>
                        <p class="text-gray-500 mb-4">You haven't had any membership periods yet.</p>
                        <a href="{{ route('member.plans') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Get Started
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Profile Management Script -->
    <script>
        // Form management
        const profileForm = document.getElementById('profileUpdateForm');
        const originalData = {
            email: document.getElementById('email').value,
            mobile_number: document.getElementById('mobile_number').value
        };

        // Reset form function
        function resetForm() {
            document.getElementById('email').value = originalData.email;
            document.getElementById('mobile_number').value = originalData.mobile_number;
            clearMessages();
        }

        // Clear all messages
        function clearMessages() {
            const messages = document.querySelectorAll('.error-message, .success-message');
            messages.forEach(msg => msg.remove());
            
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => input.classList.remove('error'));
        }

        // Show message
        function showMessage(element, message, type = 'error') {
            clearMessages();
            
            const messageDiv = document.createElement('p');
            messageDiv.className = `${type}-message`;
            messageDiv.textContent = message;
            
            element.parentNode.appendChild(messageDiv);
            
            if (type === 'error') {
                element.classList.add('error');
            }
        }

        // Form submission
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(profileForm);
            const submitBtn = profileForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = `
                <svg class="spinner" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Updating...
            `;
            submitBtn.disabled = true;
            profileForm.classList.add('loading');
            
            // Submit form
            fetch(profileForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update original data
                    originalData.email = formData.get('email');
                    originalData.mobile_number = formData.get('mobile_number');
                    
                    // Show success message
                    const emailField = document.getElementById('email');
                    showMessage(emailField, 'Profile updated successfully! Changes are now reflected across the system.', 'success');
                    
                    // Update any displayed data in the UI
                    updateDisplayedData(data.member);
                    
                } else {
                    // Show validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const fieldElement = document.getElementById(field);
                            if (fieldElement) {
                                showMessage(fieldElement, data.errors[field][0], 'error');
                            }
                        });
                    } else {
                        showMessage(document.getElementById('email'), data.message || 'An error occurred while updating your profile.', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage(document.getElementById('email'), 'A network error occurred. Please try again.', 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
                profileForm.classList.remove('loading');
            });
        });

        // Update displayed data in UI
        function updateDisplayedData(memberData) {
            // Update any elements that display member data
            const elements = document.querySelectorAll('[data-member-email]');
            elements.forEach(el => el.textContent = memberData.email);
            
            const mobileElements = document.querySelectorAll('[data-member-mobile]');
            mobileElements.forEach(el => el.textContent = memberData.mobile_number || 'Not provided');
        }

        // Auto-refresh gym status
        function updateGymStatus() {
            const statusIndicator = document.querySelector('.animate-pulse');
            if (statusIndicator) {
                statusIndicator.classList.remove('animate-pulse');
                setTimeout(() => {
                    statusIndicator.classList.add('animate-pulse');
                }, 100);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateGymStatus();
            setInterval(updateGymStatus, 10000);
            
            // Form validation on input
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        this.classList.remove('error');
                        const errorMsg = this.parentNode.querySelector('.error-message');
                        if (errorMsg) errorMsg.remove();
                    }
                });
            });
        });
    </script>
</x-layout>