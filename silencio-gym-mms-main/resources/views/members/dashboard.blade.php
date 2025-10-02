<x-layout>
    <x-nav-member></x-nav-member>
    <div class="flex-1 bg-gray-100">
        <x-topbar>Member Dashboard</x-topbar>

        <div class="bg-gray-100 p-6">
            <!-- Welcome Section with Real-time Status -->
            <div class="mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold mb-2 text-black">Welcome back, {{ $member->full_name ?? $member->first_name }}!</h1>
                            <p class="text-black font-medium">Member #{{ $member->member_number }} • {{ ucfirst($membershipStatus) }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($isInGym)
                                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                <span class="text-sm text-black font-medium">In Gym</span>
                            @else
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                <span class="text-sm text-black font-medium">Not in Gym</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Profile Summary -->
            <div class="mb-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Member Info Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border-2 border-blue-300 shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">{{ $member->full_name }}</h3>
                                <p class="text-sm text-gray-600">Member #{{ $member->member_number }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Email:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $member->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Mobile:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $member->mobile_number ?: 'Not provided' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Status:</span>
                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Membership Status Card -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border-2 border-green-300 shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Membership Status</h3>
                                <p class="text-sm text-gray-600">{{ $membershipStatus }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Current Plan:</span>
                                <span class="text-sm font-medium text-gray-900">{{ ucfirst($currentPlan ?: 'None') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Duration:</span>
                                <span class="text-sm font-medium text-gray-900">{{ ucfirst($currentDuration ?: 'N/A') }}</span>
                            </div>
                            @if($expiresAt)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Expires:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $expiresAt->format('M d, Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                     <!-- Current Session Card -->
                     <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border-2 border-purple-300 shadow-lg">
                         <div class="flex items-center mb-4">
                             <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mr-4">
                                 <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                 </svg>
                             </div>
                             <div>
                                 <h3 class="text-xl font-semibold text-gray-900">Current Session</h3>
                                 <p class="text-sm text-gray-600">{{ $isInGym ? 'Active Session' : 'No Active Session' }}</p>
                             </div>
                         </div>
                         @if($isInGym && $currentGymSession)
                             <div class="bg-white rounded-lg p-4 space-y-2">
                                 <div class="flex justify-between items-center">
                                     <span class="text-sm text-gray-600">Check-in:</span>
                                     <span class="text-sm font-medium text-gray-900" id="checkin-time">{{ $currentGymSession->check_in_time->format('M d, Y g:i A') }}</span>
                                 </div>
                                 <div class="flex justify-between items-center">
                                     <span class="text-sm text-gray-600">Duration:</span>
                                     <span class="text-sm font-medium text-purple-600" id="session-duration">{{ $currentGymSession->check_in_time->diffForHumans(null, true) }}</span>
                                 </div>
                                 <div class="flex justify-between items-center">
                                     <span class="text-sm text-gray-600">Current Time:</span>
                                     <span class="text-sm font-medium text-gray-900" id="current-time">{{ now()->format('M d, Y g:i A') }}</span>
                                 </div>
                             </div>
                         @else
                             <div class="bg-white rounded-lg p-4">
                                 <p class="text-sm text-gray-500 text-center">Not currently in gym</p>
                                 <p class="text-xs text-gray-400 text-center mt-1">Tap your RFID card to check in</p>
                                 
                                 
                                 <div class="mt-2 text-center">
                                     <span class="text-sm text-gray-600">Current Time:</span>
                                     <span class="text-sm font-medium text-gray-900" id="current-time">{{ now()->format('M d, Y g:i A') }}</span>
                                 </div>
                             </div>
                         @endif
                     </div>
                </div>
            </div>


            <!-- Enhanced Metrics Dashboard Cards -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Metrics</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Today's Attendance Card -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl shadow-lg border-2 border-green-200 p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Today</h3>
                                    <p class="text-xs text-gray-500">Attendance</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl font-bold text-green-600 mb-2">{{ $todayAttendance }}</p>
                            <p class="text-xs text-gray-500">Last updated: <span id="last-updated">{{ now()->format('H:i') }}</span></p>
                        </div>
                    </div>

                    <!-- Total Attendance Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-sky-100 rounded-xl shadow-lg border-2 border-blue-200 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Total</h3>
                                    <p class="text-xs text-gray-500">All Time Visits</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-4xl font-bold text-blue-600 mb-2">{{ $totalAttendance }}</p>
                            <p class="text-xs text-gray-500">Lifetime gym visits</p>
                        </div>
                    </div>

                    <!-- Membership Status Card -->
                    <div class="bg-gradient-to-br from-purple-50 to-violet-100 rounded-xl shadow-lg border-2 border-purple-200 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Plan</h3>
                                    <p class="text-xs text-gray-500">Membership</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-600 mb-1">{{ ucfirst($currentPlan ?? 'None') }}</p>
                            <p class="text-sm text-gray-600 mb-2">{{ ucfirst($currentDuration ?? 'N/A') }}</p>
                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $membershipStatus === 'Active' ? 'bg-green-100 text-green-800' : ($membershipStatus === 'Expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $membershipStatus }}
                            </span>
                        </div>
                    </div>

                    <!-- Days Remaining Card -->
                    <div class="bg-gradient-to-br from-orange-50 to-amber-100 rounded-xl shadow-lg border-2 border-orange-200 p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Days</h3>
                                    <p class="text-xs text-gray-500">Remaining</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            @if($expiresAt)
                                @php
                                    $expiresAtCarbon = \Carbon\Carbon::parse($expiresAt);
                                    $daysLeft = $member->days_until_expiration;
                                    
                                    // If no days left from member model, calculate from latest payment
                                    if ($daysLeft <= 0 && $member->payments()->where('status', 'completed')->exists()) {
                                        $latestPayment = $member->payments()->where('status', 'completed')->latest()->first();
                                        if ($latestPayment && $latestPayment->membership_expiration_date) {
                                            $paymentExpiresAt = \Carbon\Carbon::parse($latestPayment->membership_expiration_date);
                                            $now = \Carbon\Carbon::now();
                                            $daysLeft = max(0, (int) $now->diffInDays($paymentExpiresAt, false));
                                            $expiresAtCarbon = $paymentExpiresAt;
                                        }
                                    }
                                    
                                    // Ensure days left is a whole number
                                    $daysLeft = max(0, (int) $daysLeft);
                                @endphp
                                @if($membershipStatus === 'Expired' || $daysLeft <= 0)
                                    <p class="text-4xl font-bold text-red-600 mb-2">0</p>
                                    <p class="text-xs text-gray-500 mb-2">Expired on {{ $expiresAtCarbon->format('M d, Y') }}</p>
                                    <span class="text-xs text-red-600 font-medium">Membership Expired</span>
                                @else
                                    @php
                                        $colorClass = $daysLeft > 365 ? 'text-green-600' : ($daysLeft > 30 ? 'text-yellow-600' : 'text-red-600');
                                    @endphp
                                    <p class="text-4xl font-bold {{ $colorClass }} mb-2" id="days-remaining">{{ $daysLeft }}</p>
                                    <p class="text-xs text-gray-500 mb-2">Until {{ $expiresAtCarbon->format('M d, Y') }}</p>
                                    <span class="text-xs {{ $colorClass }} font-medium" id="days-remaining-text">{{ $daysLeft }} days remaining</span>
                                @endif
                            @else
                                <p class="text-4xl font-bold text-gray-400 mb-2">∞</p>
                                <p class="text-xs text-gray-500">No Expiry Date</p>
                                <span class="text-xs text-green-600 font-medium">Unlimited Access</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($isInGym && $currentGymSession)
            <!-- Current Gym Session Info -->
            <div class="mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold mb-2 text-black">Currently in Gym</h3>
                            <p class="text-black font-medium">Check-in time: <span id="gym-checkin-time">{{ $currentGymSession->check_in_time->format('g:i A') }}</span></p>
                            <p class="text-black font-medium">Session duration: <span id="gym-session-duration">{{ $currentGymSession->check_in_time->diffForHumans() }}</span></p>
                            <p class="text-black font-medium">Current time: <span id="gym-current-time">{{ now()->format('M d, Y g:i A') }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- Real-time update script -->
    <script>
        // Real-time clock update
        function updateRealTimeClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                second: '2-digit',
                hour12: true 
            });
            const dateString = now.toLocaleDateString('en-US', { 
                year: 'numeric',
                month: 'short', 
                day: 'numeric' 
            });
            const fullDateTime = `${dateString} ${timeString}`;
            
            // Update all current time elements
            const currentTimeElements = document.querySelectorAll('#current-time, #gym-current-time');
            currentTimeElements.forEach(element => {
                if (element) {
                    element.textContent = fullDateTime;
                }
            });
            
            // Update last updated timestamp
            const timestampElement = document.getElementById('last-updated');
            if (timestampElement) {
                timestampElement.textContent = timeString;
            }
        }

        // Update session duration in real-time
        function updateSessionDuration() {
            const checkinTimeElement = document.getElementById('checkin-time');
            const sessionDurationElement = document.getElementById('session-duration');
            const gymCheckinTimeElement = document.getElementById('gym-checkin-time');
            const gymSessionDurationElement = document.getElementById('gym-session-duration');
            
            if (checkinTimeElement && sessionDurationElement) {
                const checkinTimeText = checkinTimeElement.textContent;
                const checkinDate = new Date(checkinTimeText);
                const now = new Date();
                const diffMs = now - checkinDate;
                
                if (diffMs > 0) {
                    const hours = Math.floor(diffMs / (1000 * 60 * 60));
                    const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diffMs % (1000 * 60)) / 1000);
                    
                    let durationText = '';
                    if (hours > 0) {
                        durationText = `${hours}h ${minutes}m`;
                    } else if (minutes > 0) {
                        durationText = `${minutes}m ${seconds}s`;
                    } else {
                        durationText = `${seconds}s`;
                    }
                    
                    sessionDurationElement.textContent = durationText;
                }
            }
            
            if (gymCheckinTimeElement && gymSessionDurationElement) {
                const checkinTimeText = gymCheckinTimeElement.textContent;
                const checkinDate = new Date(checkinTimeText);
                const now = new Date();
                const diffMs = now - checkinDate;
                
                if (diffMs > 0) {
                    const hours = Math.floor(diffMs / (1000 * 60 * 60));
                    const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
                    
                    let durationText = '';
                    if (hours > 0) {
                        durationText = `${hours} hours, ${minutes} minutes ago`;
                    } else if (minutes > 0) {
                        durationText = `${minutes} minutes ago`;
                    } else {
                        durationText = 'Just now';
                    }
                    
                    gymSessionDurationElement.textContent = durationText;
                }
            }
        }

        // Update days remaining with color coding
        function updateDaysRemaining() {
            const daysRemainingElement = document.getElementById('days-remaining');
            const daysRemainingTextElement = document.getElementById('days-remaining-text');
            
            if (daysRemainingElement && daysRemainingTextElement) {
                const daysLeft = parseInt(daysRemainingElement.textContent);
                let colorClass = '';
                
                if (daysLeft > 365) {
                    colorClass = 'text-green-600';
                } else if (daysLeft > 30) {
                    colorClass = 'text-yellow-600';
                } else {
                    colorClass = 'text-red-600';
                }
                
                // Update color classes
                daysRemainingElement.className = `text-4xl font-bold ${colorClass} mb-2`;
                daysRemainingTextElement.className = `text-xs ${colorClass} font-medium`;
                
                // Update text content
                if (daysLeft > 0) {
                    daysRemainingTextElement.textContent = `${daysLeft} days remaining`;
                } else {
                    daysRemainingTextElement.textContent = 'Membership Expired';
                }
            }
        }

        // Update gym presence status indicator
        function updateGymStatus() {
            const statusIndicator = document.querySelector('.animate-pulse');
            if (statusIndicator) {
                statusIndicator.classList.remove('animate-pulse');
                setTimeout(() => {
                    statusIndicator.classList.add('animate-pulse');
                }, 100);
            }
        }

        // Initialize real-time updates
        document.addEventListener('DOMContentLoaded', function() {
            // Update immediately
            updateRealTimeClock();
            updateSessionDuration();
            updateDaysRemaining();
            
            // Update clock every second
            setInterval(updateRealTimeClock, 1000);
            
            // Update session duration every 10 seconds
            setInterval(updateSessionDuration, 10000);
            
            // Update gym status every 5 seconds
        setInterval(updateGymStatus, 5000);

            // Update days remaining every minute
            setInterval(updateDaysRemaining, 60000);
        });
    </script>


    <!-- Member NFC Integration Script -->
    <script>
        // NFC Support Detection for Members
        let memberNfcSupported = false;
        let memberNfcReader = null;

        // Check NFC support on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkMemberNfcSupport();
            setupMemberFloatingButton();
            setupMemberBraveHelp();
        });

        // Browser detection for members
        async function detectMemberBrowser() {
            const userAgent = navigator.userAgent;
            
            // Check for Brave browser
            if (navigator.brave) {
                try {
                    const isBrave = await navigator.brave.isBrave();
                    if (isBrave) {
                        return 'brave';
                    }
                } catch (error) {
                    console.log('Brave detection failed:', error);
                }
            }
            
            // Fallback to user agent detection
            if (userAgent.includes('Chrome')) {
                return 'chrome';
            } else if (userAgent.includes('Firefox')) {
                return 'firefox';
            } else if (userAgent.includes('Safari')) {
                return 'safari';
            } else {
                return 'unknown';
            }
        }

        // Check if Web NFC is supported
        async function checkMemberNfcSupport() {
            try {
                const browser = await detectMemberBrowser();
                console.log('🌐 Member browser detected:', browser);
                
                if ('NDEFReader' in window) {
                    memberNfcSupported = true;
                    console.log('✅ Web NFC is supported for member dashboard');
                    
                    // Check for Brave-specific issues
                    if (browser === 'brave') {
                        console.log('🦁 Brave browser detected - checking NFC configuration');
                        
                        // Check if we're on HTTPS
                        if (location.protocol !== 'https:') {
                            console.log('⚠️ Brave requires HTTPS for NFC');
                            showMemberNotification('NFC requires HTTPS in Brave browser. Please use HTTPS or enable NFC in brave://flags', 'warning');
                        }
                    }
                    
                    // Show floating button on mobile devices
                    if (window.innerWidth <= 768) {
                        document.getElementById('member-floating-nfc-btn').classList.remove('hidden');
                    }
                } else {
                    memberNfcSupported = false;
                    console.log('❌ Web NFC is not supported for member dashboard');
                    
                    if (browser === 'brave') {
                        console.log('🦁 Brave browser - NFC may be disabled in flags');
                        showMemberNotification('NFC is disabled in Brave. Enable it in brave://flags/#enable-web-nfc', 'info');
                    }
                    
                    // Hide NFC button if not supported
                    const nfcButton = document.getElementById('member-nfc-checkin-btn');
                    if (nfcButton) {
                        nfcButton.style.display = 'none';
                    }
                }
            } catch (error) {
                memberNfcSupported = false;
                console.log('❌ Web NFC check failed:', error);
            }
        }

        // Setup floating button visibility
        function setupMemberFloatingButton() {
            // Show floating button on mobile devices if NFC is supported
            if (memberNfcSupported && window.innerWidth <= 768) {
                document.getElementById('member-floating-nfc-btn').classList.remove('hidden');
            }
        }

        // Setup member Brave help section
        async function setupMemberBraveHelp() {
            try {
                const browser = await detectMemberBrowser();
                const braveHelpSection = document.getElementById('member-brave-help-section');
                
                if (braveHelpSection && browser === 'brave' && !memberNfcSupported) {
                    braveHelpSection.classList.remove('hidden');
                } else if (braveHelpSection) {
                    braveHelpSection.classList.add('hidden');
                }
            } catch (error) {
                console.error('❌ Error in setupMemberBraveHelp function:', error);
            }
        }

        // Show member Brave-specific instructions
        function showMemberBraveInstructions() {
            const instructions = `
                <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-lg p-6 max-w-md w-full">
                        <div class="text-center mb-4">
                            <div class="text-4xl mb-2">🦁</div>
                            <h3 class="text-lg font-semibold">Enable NFC in Brave Browser</h3>
                        </div>
                        <div class="text-sm text-gray-600 mb-4">
                            <p class="mb-3">Brave browser blocks NFC by default for security. To enable it:</p>
                            <ol class="list-decimal list-inside space-y-2">
                                <li>Open <code class="bg-gray-100 px-1 rounded">brave://flags</code> in your address bar</li>
                                <li>Search for "Web NFC"</li>
                                <li>Enable the "Web NFC" flag</li>
                                <li>Restart Brave browser</li>
                                <li>Return to this page and try NFC again</li>
                            </ol>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                                Close
                            </button>
                            <button onclick="window.open('brave://flags', '_blank')" 
                                    class="flex-1 px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">
                                Open Flags
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', instructions);
        }

        // Start member NFC check-in
        async function startMemberNfcCheckIn() {
            try {
                const browser = await detectMemberBrowser();
                console.log('🌐 Starting member NFC check-in on:', browser);
                
                if (!memberNfcSupported) {
                    if (browser === 'brave') {
                        showMemberNotification('NFC is disabled in Brave. Enable it in brave://flags/#enable-web-nfc', 'error');
                    } else {
                        showMemberNotification('NFC is not supported on this device', 'error');
                    }
                    return;
                }

                const button = document.getElementById('member-nfc-checkin-btn');
                const floatingButton = document.getElementById('member-floating-nfc-btn');
                const originalText = button ? button.innerHTML : '';
                
                if (button) {
                    button.innerHTML = '<span class="text-lg sm:text-xl">⏳</span><span class="text-sm sm:text-base">Scanning...</span>';
                    button.disabled = true;
                }

                try {
                    // Brave-specific checks
                    if (browser === 'brave') {
                        // Check HTTPS requirement
                        if (location.protocol !== 'https:') {
                            throw new Error('Brave requires HTTPS for NFC. Please use HTTPS or enable NFC in brave://flags');
                        }
                        
                        // Check if NFC is enabled in flags
                        console.log('🦁 Brave browser - checking NFC configuration');
                    }
                    
                    // Request NFC permission
                    memberNfcReader = new NDEFReader();
                    await memberNfcReader.scan();
                    
                    showMemberNotification('NFC ready! Tap your phone to an NFC tag', 'success');
                    
                    // Listen for NFC reads
                    memberNfcReader.addEventListener('reading', handleMemberNfcRead);
                    memberNfcReader.addEventListener('readingerror', handleMemberNfcError);
                    
                } catch (error) {
                    console.error('Member NFC Error:', error);
                    
                    // Brave-specific error handling
                    if (browser === 'brave') {
                        if (error.message.includes('HTTPS')) {
                            showMemberNotification('Brave requires HTTPS for NFC. Please use HTTPS or enable NFC in brave://flags', 'error');
                        } else if (error.message.includes('permission')) {
                            showMemberNotification('NFC permission denied in Brave. Check brave://flags/#enable-web-nfc', 'error');
                        } else {
                            showMemberNotification('NFC error in Brave: ' + error.message + '. Check brave://flags/#enable-web-nfc', 'error');
                        }
                    } else {
                        showMemberNotification('NFC access denied or error: ' + error.message, 'error');
                    }
                    
                    if (button) {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                }
            } catch (error) {
                console.error('❌ Error in startMemberNfcCheckIn function:', error);
                showMemberNotification('Error initializing NFC: ' + error.message, 'error');
            }
        }

        // Handle member NFC read event
        function handleMemberNfcRead(event) {
            console.log('Member NFC Tag detected:', event);
            
            try {
                // Extract UID from NFC tag
                let uid = '';
                if (event.serialNumber) {
                    // Convert serial number to hex string
                    uid = Array.from(event.serialNumber)
                        .map(byte => byte.toString(16).padStart(2, '0'))
                        .join('')
                        .toUpperCase();
                } else {
                    // Generate a mock UID for testing
                    uid = 'NFC' + Date.now().toString(16).substr(-8).toUpperCase();
                }

                console.log('Member NFC UID:', uid);
                
                // Send to RFID API endpoint
                sendMemberNfcToRfidApi(uid);
                
            } catch (error) {
                console.error('Error processing member NFC read:', error);
                showMemberNotification('Error processing NFC tag: ' + error.message, 'error');
            }
        }

        // Handle member NFC read error
        function handleMemberNfcError(event) {
            console.error('Member NFC Read Error:', event);
            showMemberNotification('NFC read error: ' + event.message, 'error');
        }

        // Send member NFC data to RFID API
        async function sendMemberNfcToRfidApi(uid) {
            try {
                const response = await fetch('{{ route("rfid.tap") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        card_uid: uid,
                        device_id: 'nfc_member',
                        source: 'nfc'
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    showMemberNotification('NFC check-in successful!', 'success');
                    
                    // Refresh the page to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                    
                } else {
                    showMemberNotification('NFC check-in failed: ' + (data.message || 'Unknown error'), 'error');
                }
                
            } catch (error) {
                console.error('Member API Error:', error);
                showMemberNotification('Failed to process NFC check-in: ' + error.message, 'error');
            }
        }

        // Show member notification
        function showMemberNotification(message, type) {
            const bgColor = type === 'success' ? '#059669' : type === 'warning' ? '#D97706' : type === 'info' ? '#2563EB' : '#DC2626';
            const textColor = '#FFFFFF';
            
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg';
            notification.style.backgroundColor = bgColor;
            notification.style.color = textColor;
            notification.style.maxWidth = '300px';
            notification.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // Reset member NFC buttons after use
        function resetMemberNfcButtons() {
            const button = document.getElementById('member-nfc-checkin-btn');
            
            if (button) {
                button.innerHTML = '<span class="text-lg sm:text-xl">📱</span><span class="text-sm sm:text-base">Tap Phone to Check-In</span>';
                button.disabled = false;
            }
        }

        // Handle window resize for floating button
        window.addEventListener('resize', function() {
            const floatingBtn = document.getElementById('member-floating-nfc-btn');
            if (window.innerWidth <= 768 && memberNfcSupported) {
                floatingBtn.classList.remove('hidden');
            } else {
                floatingBtn.classList.add('hidden');
            }
        });

        // Auto-reset NFC buttons after 30 seconds
        setTimeout(resetMemberNfcButtons, 30000);
    </script>
</x-layout>


