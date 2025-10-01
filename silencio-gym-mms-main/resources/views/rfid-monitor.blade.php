<x-layout>
    <x-nav></x-nav>
    <div class="flex-1 bg-white">
        <x-topbar>RFID Monitor</x-topbar>

        <div class="bg-white p-4 sm:p-6">
            <!-- Metrics Section -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-lg border p-4 sm:p-6 lg:p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6" style="color: #1E40AF;">Today's Metrics</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div class="bg-white border rounded-lg p-4 sm:p-6" style="border-color: #059669; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs sm:text-sm font-medium mb-2" style="color: #6B7280;">Today's Check-ins</p>
                                    <p class="text-2xl sm:text-3xl font-bold" style="color: #059669;" id="todays-checkins">-</p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center" style="background-color: #059669;">
                                    <span class="text-lg sm:text-2xl text-white">✅</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-4 sm:p-6" style="border-color: #DC2626; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs sm:text-sm font-medium mb-2" style="color: #6B7280;">Expired Memberships</p>
                                    <p class="text-2xl sm:text-3xl font-bold" style="color: #DC2626;" id="expired-memberships">-</p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center" style="background-color: #DC2626;">
                                    <span class="text-lg sm:text-2xl text-white">⏰</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-4 sm:p-6 sm:col-span-2 lg:col-span-1" style="border-color: #8B5CF6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs sm:text-sm font-medium mb-2" style="color: #6B7280;">Unknown Cards</p>
                                    <p class="text-2xl sm:text-3xl font-bold" style="color: #8B5CF6;" id="unknown-cards">-</p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center" style="background-color: #8B5CF6;">
                                    <span class="text-lg sm:text-2xl text-white">❓</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RFID Control Panel -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-lg border p-4 sm:p-6 lg:p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-3">
                        <h2 class="text-xl sm:text-2xl font-bold" style="color: #1E40AF;">RFID Control</h2>
                        <div class="flex items-center gap-3">
                            <div id="rfid-control-status-indicator" class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span id="rfid-control-status-text" class="text-xs sm:text-sm font-medium" style="color: #DC2626;">System Offline</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-6">
                        <button onclick="startRfidSystem()" id="start-rfid-control-btn" 
                                class="px-6 sm:px-8 py-3 sm:py-4 text-white text-base sm:text-lg rounded-lg flex items-center justify-center gap-3 min-h-[44px] w-full sm:w-auto" 
                                style="background-color: #059669;">
                            <span class="text-lg sm:text-xl">▶️</span>
                            <span class="text-sm sm:text-base">Start RFID</span>
                        </button>
                        
                        <button onclick="stopRfidSystem()" id="stop-rfid-control-btn" 
                                class="px-6 sm:px-8 py-3 sm:py-4 text-white text-base sm:text-lg rounded-lg flex items-center justify-center gap-3 min-h-[44px] w-full sm:w-auto" 
                                style="background-color: #DC2626;">
                            <span class="text-lg sm:text-xl">⏹️</span>
                            <span class="text-sm sm:text-base">Stop RFID</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Currently Active Members -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-lg border p-4 sm:p-6 lg:p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="mb-4 sm:mb-6">
                        <h2 class="text-xl sm:text-2xl font-bold" style="color: #1E40AF;">Currently Active Members</h2>
                    </div>
                    
                    <div id="active-members-list" class="space-y-3">
                    </div>
                </div>
            </div>

            <!-- Recent RFID Activity -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-lg border p-4 sm:p-6 lg:p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-3">
                        <h2 class="text-xl sm:text-2xl font-bold" style="color: #1E40AF;">Recent RFID Activity</h2>
                        <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4">
                            <select id="log-filter" 
                                    class="w-full sm:w-auto px-3 sm:px-4 py-2 border rounded-lg text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                    style="border-color: #E5E7EB;">
                                <option value="">All Actions</option>
                                <option value="check_in">Check-ins</option>
                                <option value="check_out">Check-outs</option>
                                <option value="unknown_card">Unknown Cards</option>
                                <option value="expired_membership">Expired Memberships</option>
                            </select>
                            <button onclick="refreshLogs()" 
                                    class="w-full sm:w-auto px-3 sm:px-4 py-2 text-white text-xs sm:text-sm rounded-lg flex items-center justify-center gap-2 min-h-[44px]" 
                                    style="background-color: #2563EB;">
                                <span class="text-base sm:text-lg">🔄</span>
                                <span class="text-xs sm:text-sm">Refresh</span>
                            </button>
                        </div>
                    </div>
                    
                    <div id="rfid-logs-list" class="space-y-2">
                        <div class="text-center py-8" style="color: #6B7280;">
                            <p>Loading RFID logs...</p>
                        </div>
                    </div>
                    
                    <!-- Pagination Controls -->
                    <div id="rfid-logs-pagination" class="mt-4 sm:mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-xs sm:text-sm" style="color: #6B7280;">
                            <span id="logs-info">Showing 0 to 0 of 0 results</span>
                        </div>
                        <div class="flex items-center space-x-1 sm:space-x-2">
                            <button id="prev-page" onclick="changePage(-1)" 
                                    class="px-2 sm:px-4 py-2 text-xs sm:text-sm font-medium border rounded-lg disabled:opacity-50 disabled:cursor-not-allowed" 
                                    style="border-color: #E5E7EB; color: #6B7280;">
                                Previous
                            </button>
                            <span id="page-info" class="px-2 sm:px-4 py-2 text-xs sm:text-sm" style="color: #6B7280;">Page 1</span>
                            <button id="next-page" onclick="changePage(1)" 
                                    class="px-2 sm:px-4 py-2 text-xs sm:text-sm font-medium border rounded-lg disabled:opacity-50 disabled:cursor-not-allowed" 
                                    style="border-color: #E5E7EB; color: #6B7280;">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Pagination variables
        let currentPage = 1;
        let totalPages = 1;
        let totalRecords = 0;
        let currentFilter = '';

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('RFID Monitor initialized');
            
            // Check RFID system status on load
            checkRfidStatus();
            
            // Load initial data
            loadDashboardStats();
            loadActiveMembers();
            loadRfidLogs();
            
            // Auto-refresh every 1 second for real-time updates
            setInterval(function() {
                console.log('Auto-refreshing RFID Monitor data...');
                loadDashboardStats();
                loadActiveMembers();
                loadRfidLogs(currentPage); // Maintain current page during auto-refresh
            }, 1000);
            
            // Check RFID status every 10 seconds
            setInterval(function() {
                checkRfidStatus();
            }, 10000);
            
        });

        // Load dashboard statistics
        function loadDashboardStats() {
            console.log('🔄 Loading dashboard stats...');
            fetch('{{ route("dashboard.stats") }}')
                .then(response => {
                    console.log('📡 Dashboard stats response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📊 Dashboard stats loaded:', data);
                    document.getElementById('today-checkins').textContent = data.today_attendance;
                    document.getElementById('expired-memberships').textContent = data.expired_memberships_today;
                    document.getElementById('unknown-cards').textContent = data.unknown_cards_today;
                })
                .catch(error => {
                    console.error('Error loading dashboard stats:', error);
                    // Show error indicator
                    document.getElementById('today-checkins').textContent = 'Error';
                    document.getElementById('expired-memberships').textContent = 'Error';
                    document.getElementById('unknown-cards').textContent = 'Error';
                });
        }

        // Load active members
        function loadActiveMembers() {
            console.log('🔄 Loading active members...');
            fetch('{{ route("rfid.active-members") }}')
                .then(response => {
                    console.log('📡 Active members response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📊 Active members data received:', data);
                    const container = document.getElementById('active-members-list');
                    
                    if (!data.success) {
                        throw new Error('API returned error: ' + (data.message || 'Unknown error'));
                    }
                    
                    console.log('👥 Active members count:', data.count);
                    console.log('👥 Active members list:', data.active_members);
                    
                    if (data.active_members.length === 0) {
                        console.log('📭 No active members, showing empty message');
                        container.innerHTML = '';
                        return;
                    }
                    
                    container.innerHTML = data.active_members.map(member => `
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 sm:p-4 rounded-lg border gap-3" style="background-color: #F9FAFB; border-color: #E5E7EB;">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center" style="background-color: #059669;">
                                    <span class="text-lg sm:text-xl text-white">✅</span>
                                </div>
                                <div>
                                    <p class="text-sm sm:text-base font-semibold" style="color: #000000;">${member.name}</p>
                                    <p class="text-xs sm:text-sm" style="color: #6B7280;">${member.membership_plan} • Checked in at ${formatTime(member.check_in_time)}</p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-xs sm:text-sm font-medium" style="color: #059669;">${member.session_duration}</p>
                                <p class="text-xs" style="color: #6B7280;">Session duration</p>
                            </div>
                        </div>
                    `).join('');
                })
                .catch(error => {
                    console.error('Error loading active members:', error);
                    // Keep container empty on error
                });
        }

        // Load RFID logs
        function loadRfidLogs(page = 1) {
            console.log('🔄 Loading RFID logs, page:', page);
            const filter = document.getElementById('log-filter').value;
            const url = new URL('{{ route("rfid.logs") }}', window.location.origin);
            
            if (filter) {
                url.searchParams.set('action', filter);
            }
            if (page > 1) {
                url.searchParams.set('page', page);
            }
            
            console.log('📡 RFID logs URL:', url.toString());
            
            fetch(url)
                .then(response => {
                    console.log('📡 RFID logs response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📊 RFID logs data received:', data);
                    const container = document.getElementById('rfid-logs-list');
                    
                    if (!data.success) {
                        throw new Error('API returned error: ' + (data.message || 'Unknown error'));
                    }
                    
                    console.log('📋 RFID logs count:', data.logs.data.length);
                    
                    if (data.logs.data.length === 0) {
                        container.innerHTML = '<div class="text-center py-8" style="color: #6B7280;"><p>No RFID logs found</p></div>';
                        updatePaginationInfo(data.logs);
                        return;
                    }
                    
                    container.innerHTML = data.logs.data.map((log, index) => {
                        const logConfig = getLogConfig(log.action, log.status);
                        const timestamp = new Date(log.timestamp);
                        const formattedTime = formatTimeWithDate(timestamp);
                        
                        return `
                            <div class="flex flex-col sm:flex-row sm:items-center p-3 sm:p-4 rounded-lg border-l-4 gap-3" 
                                 style="background-color: ${index % 2 === 0 ? '#FFFFFF' : '#F9FAFB'}; border-left-color: ${logConfig.borderColor}; border-color: #E5E7EB;">
                                <div class="flex items-center gap-3 sm:gap-4 flex-1">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center" style="background-color: ${logConfig.bgColor};">
                                        <span class="text-lg sm:text-xl text-white">${logConfig.icon}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 mb-1">
                                            <p class="text-sm sm:text-base font-semibold" style="color: #000000;">${logConfig.memberName || 'Unknown Member'}</p>
                                            <span class="text-xs sm:text-sm px-2 py-1 rounded-full text-white" style="background-color: ${logConfig.badgeColor};">
                                                ${logConfig.actionText}
                                            </span>
                                        </div>
                                        <p class="text-xs sm:text-sm" style="color: #6B7280;">${logConfig.description}</p>
                                        <p class="text-xs" style="color: #6B7280;">Card: ${log.card_uid}</p>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="text-xs sm:text-sm font-medium" style="color: #000000;">${formattedTime}</p>
                                </div>
                            </div>
                        `;
                    }).join('');
                    
                    updatePaginationInfo(data.logs);
                })
                .catch(error => {
                    console.error('Error loading RFID logs:', error);
                    document.getElementById('rfid-logs-list').innerHTML = '<div class="text-center py-8" style="color: #DC2626;"><p>Error loading RFID logs: ' + error.message + '</p></div>';
                });
        }

        // Get log configuration based on action and status
        function getLogConfig(action, status) {
            const configs = {
                'check_in': {
                    icon: '✅',
                    borderColor: '#059669',
                    bgColor: '#059669',
                    badgeColor: '#059669',
                    actionText: 'Checked in successfully',
                    description: 'Member successfully checked in',
                    memberName: null // Will be extracted from log message
                },
                'check_out': {
                    icon: '🔄',
                    borderColor: '#D97706',
                    bgColor: '#D97706',
                    badgeColor: '#D97706',
                    actionText: 'Checked out',
                    description: 'Member checked out',
                    memberName: null
                },
                'unknown_card': {
                    icon: '❓',
                    borderColor: '#8B5CF6',
                    bgColor: '#8B5CF6',
                    badgeColor: '#8B5CF6',
                    actionText: 'Unknown Member',
                    description: 'Unknown card detected',
                    memberName: 'Unknown Member'
                },
                'expired_membership': {
                    icon: '❌',
                    borderColor: '#DC2626',
                    bgColor: '#DC2626',
                    badgeColor: '#DC2626',
                    actionText: 'Expired Membership',
                    description: 'Membership has expired',
                    memberName: null
                }
            };
            
            return configs[action] || {
                icon: '❓',
                borderColor: '#8B5CF6',
                bgColor: '#8B5CF6',
                badgeColor: '#8B5CF6',
                actionText: 'Unknown Action',
                description: 'Unknown action detected',
                memberName: 'Unknown Member'
            };
        }

        // Format time to 12-hour format with AM/PM
        function formatTime(timeString) {
            if (!timeString) return 'N/A';
            const date = new Date(timeString);
            return date.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
        }

        // Format time with full date
        function formatTimeWithDate(date) {
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
                });
        }

        // Play feedback sound
        function playFeedbackSound(type) {
            // Create audio context for sound feedback
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                if (type === 'success') {
                    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                    oscillator.frequency.setValueAtTime(1000, audioContext.currentTime + 0.1);
                } else if (type === 'error') {
                    oscillator.frequency.setValueAtTime(400, audioContext.currentTime);
                    oscillator.frequency.setValueAtTime(200, audioContext.currentTime + 0.1);
                } else if (type === 'warning') {
                    oscillator.frequency.setValueAtTime(600, audioContext.currentTime);
                }
                
                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.2);
            } catch (e) {
                console.log('Audio not supported');
            }
        }


        function refreshLogs() {
            currentPage = 1; // Reset to first page when manually refreshing
            loadRfidLogs();
        }

        // Update pagination information
        function updatePaginationInfo(logs) {
            currentPage = logs.current_page;
            totalPages = logs.last_page;
            totalRecords = logs.total;
            
            const startRecord = logs.from || 0;
            const endRecord = logs.to || 0;
            
            document.getElementById('logs-info').textContent = `Showing ${startRecord} to ${endRecord} of ${totalRecords} results`;
            document.getElementById('page-info').textContent = `Page ${currentPage} of ${totalPages}`;
            
            // Update button states
            document.getElementById('prev-page').disabled = currentPage <= 1;
            document.getElementById('next-page').disabled = currentPage >= totalPages;
        }
        
        // Change page
        function changePage(direction) {
            const newPage = currentPage + direction;
            if (newPage >= 1 && newPage <= totalPages) {
                loadRfidLogs(newPage);
            }
        }
        
        // Filter logs when selection changes
        document.getElementById('log-filter').addEventListener('change', function() {
            currentPage = 1; // Reset to first page when filter changes
            loadRfidLogs();
        });

        // Real-time Updates Configuration
        let realTimeUpdatesEnabled = false;
        let updateInterval = null;

        // RFID System Management Functions
        function startRfidSystem() {
            console.log('🚀 Starting RFID system...');
            const button = document.getElementById('start-rfid-control-btn');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<span class="text-lg sm:text-xl">⏳</span><span class="text-sm sm:text-base">Starting...</span>';
            button.disabled = true;
            
            fetch('{{ route("rfid.start") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('📊 RFID start response:', data);
                    
                    if (data.success) {
                        console.log('✅ RFID system started successfully');
                        // Immediately update status to online
                        updateRfidStatus(true, data.message);
                        
                        // Enable real-time updates
                        enableRealTimeUpdates();
                        
                        // Refresh data after starting
                        setTimeout(() => {
                            loadDashboardStats();
                            loadActiveMembers();
                            loadRfidLogs();
                        }, 2000);
                        
                        // Show success notification
                        showNotification('RFID system started successfully', 'success');
                    } else {
                        console.log('❌ RFID startup response:', data.message);
                        
                        // Don't show error notification if it's a hardware detection issue
                        if (data.message.includes('hardware not detected')) {
                            console.log('⚠️ Hardware detection warning ignored - continuing startup');
                            updateRfidStatus(true, 'RFID system starting...');
                            enableRealTimeUpdates();
                            showNotification('RFID system starting (hardware detection in progress)', 'info');
                        } else {
                        updateRfidStatus(false, data.message);
                            showNotification('Failed to start RFID: ' + data.message, 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('❌ Error starting RFID system:', error);
                    updateRfidStatus(false, 'Error starting RFID system');
                    showNotification('Error starting RFID system: ' + error.message, 'error');
                })
                .finally(() => {
                    // Restore button
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }

        
        function checkRfidStatus() {
            fetch('{{ route("rfid.status") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateRfidStatus(data.rfid_reader_running, data.message);
                    }
                })
                .catch(error => {
                    console.error('Error checking RFID status:', error);
                    updateRfidStatus(false, 'Error checking status');
                });
        }
        
        function stopRfidSystem() {
            console.log('🛑 Stopping RFID system...');
            const button = document.getElementById('stop-rfid-control-btn');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Stopping...';
            button.disabled = true;
            
            // Stop Python processes
            fetch('{{ route("rfid.stop") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('📊 RFID stop response:', data);
                    
                    if (data.success) {
                        console.log('✅ RFID system stopped successfully');
                        updateRfidStatus(false, data.message);
                        // Disable real-time updates
                        disableRealTimeUpdates();
                    } else {
                        console.log('❌ Failed to stop RFID system:', data.message);
                        updateRfidStatus(true, data.message);
                        showNotification('Failed to stop RFID: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('❌ Error stopping RFID system:', error);
                    updateRfidStatus(false, 'Error stopping RFID system');
                    showNotification('Error stopping RFID system: ' + error.message, 'error');
                })
                .finally(() => {
                    // Restore button
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }
        
        function updateRfidStatus(isRunning, message) {
            const controlIndicator = document.getElementById('rfid-control-status-indicator');
            const controlStatusText = document.getElementById('rfid-control-status-text');
            
            if (isRunning) {
                controlIndicator.className = 'w-3 h-3 bg-green-500 rounded-full';
                controlIndicator.style.backgroundColor = '#059669';
                controlStatusText.textContent = 'System Online';
                controlStatusText.style.color = '#059669';
            } else {
                controlIndicator.className = 'w-3 h-3 bg-red-500 rounded-full';
                controlIndicator.style.backgroundColor = '#DC2626';
                controlStatusText.textContent = 'System Offline';
                controlStatusText.style.color = '#DC2626';
            }
        }

        // Real-time Update Functions
        function enableRealTimeUpdates() {
            realTimeUpdatesEnabled = true;
            updateInterval = setInterval(() => {
                if (realTimeUpdatesEnabled) {
                    // Update logs every 5 seconds
                    loadRfidLogs();
                    // Update dashboard stats every 10 seconds
                    loadDashboardStats();
                    // Note: loadActiveMembers() removed to prevent flashing
                }
            }, 5000);
            console.log('✅ Real-time updates enabled');
        }

        function disableRealTimeUpdates() {
            realTimeUpdatesEnabled = false;
            if (updateInterval) {
                clearInterval(updateInterval);
                updateInterval = null;
            }
            console.log('❌ Real-time updates disabled');
        }

        // Notification System
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            const bgColor = type === 'error' ? '#DC2626' : type === 'success' ? '#059669' : '#2563EB';
            const textColor = '#FFFFFF';
            
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

        // Load Dashboard Stats Function
        function loadDashboardStats() {
            console.log('🔄 Loading dashboard stats...');
            fetch('{{ route("rfid.dashboard-stats") }}', {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('📡 Dashboard stats response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('📊 Dashboard stats data received:', data);
                if (data.success) {
                    const stats = data.stats;
                    document.getElementById('today-checkins').textContent = stats.today_checkins || '0';
                    document.getElementById('expired-memberships').textContent = stats.expired_memberships || '0';
                    document.getElementById('unknown-cards').textContent = stats.unknown_cards || '0';
                    console.log('✅ Dashboard stats updated:', stats);
                } else {
                    console.log('❌ Dashboard stats failed:', data.message);
                    // Set to 0 if call fails
                    document.getElementById('today-checkins').textContent = '0';
                    document.getElementById('expired-memberships').textContent = '0';
                    document.getElementById('unknown-cards').textContent = '0';
                }
            })
            .catch(error => {
                console.error('❌ Error loading dashboard stats:', error);
                // Set to 0 on error
                document.getElementById('today-checkins').textContent = '0';
                document.getElementById('expired-memberships').textContent = '0';
                document.getElementById('unknown-cards').textContent = '0';
            });
        }

        // Load Active Members Function
        function loadActiveMembers() {
            console.log('🔄 Loading active members...');
            fetch('{{ route("rfid.active-members") }}', {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('📡 Active members response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('👥 Active members data received:', data);
                const container = document.getElementById('active-members-list');
                
                if (data.success && data.active_members && data.active_members.length > 0) {
                    console.log('✅ Displaying', data.active_members.length, 'active members');
                    container.innerHTML = data.active_members.map(member => `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border" style="border-color: #E5E7EB;">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-green-600">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-sm" style="color: #000000;">${member.name}</p>
                                    <p class="text-xs" style="color: #6B7280;">${member.membership_plan} • Checked in at ${member.check_in_time}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium" style="color: #059669;">${member.session_duration}</p>
                                <p class="text-xs" style="color: #6B7280;">Active</p>
                            </div>
                        </div>
                    `).join('');
                } else {
                    console.log('ℹ️ No active members found');
                    container.innerHTML = '';
                }
            })
            .catch(error => {
                console.error('❌ Error loading active members:', error);
                // Keep container empty on error
            });
        }

        // Load RFID Logs Function
        function loadRfidLogs(page = 1) {
            const filter = document.getElementById('log-filter').value;
            const url = `{{ route('rfid.logs') }}?page=${page}${filter ? '&action=' + filter : ''}`;
            
            fetch(url, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('rfid-logs-list');
                
                if (data.success && data.logs.data.length > 0) {
                    container.innerHTML = data.logs.data.map(log => {
                        const config = getLogConfig(log.action, log.status);
                        return `
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border hover:bg-gray-50 transition-colors" style="border-color: #E5E7EB;">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: ${config.bgColor};">
                                        <span class="text-white text-xs">${config.icon}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm" style="color: #000000;">${log.message}</p>
                                        <p class="text-xs" style="color: #6B7280;">${formatTimeWithDate(new Date(log.timestamp))}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full text-white" style="background-color: ${config.badgeColor};">
                                        ${config.actionText}
                                    </span>
                                </div>
                            </div>
                        `;
                    }).join('');
                    
                    updatePaginationInfo(data.logs);
                } else {
                    container.innerHTML = `
                        <div class="text-center py-8" style="color: #6B7280;">
                            <div class="text-6xl mb-4">📋</div>
                            <p class="text-lg font-medium mb-2" style="color: #000000;">No RFID activity logs found</p>
                            <p class="text-sm">Activity will appear here when RFID cards are used</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('❌ Error loading RFID logs:', error);
            });
        }

        // Real-time data refresh intervals
        function startDataRefresh() {
            console.log('🔄 Starting data refresh intervals...');
            
            // Refresh dashboard stats every 10 seconds
            setInterval(loadDashboardStats, 10000);
            
            // Note: loadActiveMembers() removed to prevent flashing
            
            // Refresh RFID logs every 10 seconds
            setInterval(loadRfidLogs, 10000);
            
            // Check RFID status every 30 seconds
            setInterval(checkRfidStatus, 30000);
        }
        
        // Initialize page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Initializing RFID Monitor...');
            
            // Load initial data
            loadDashboardStats();
            // Note: loadActiveMembers() removed to prevent flashing
            loadRfidLogs();
            checkRfidStatus();
            
            // Start automatic data refresh
            startDataRefresh();
            
            // Show welcome notification
            setTimeout(() => {
                showNotification('RFID Monitor initialized successfully', 'success');
            }, 1000);
        });


    </script>
</x-layout>
