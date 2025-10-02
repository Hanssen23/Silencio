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
                                    <p class="text-xs sm:text-sm font-medium mb-2" style="color: #6B7280;">Recent Check-ins (24h)</p>
                                    <p class="text-2xl sm:text-3xl font-bold" style="color: #059669;" id="todays-checkins">0</p>
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
                                    <p class="text-2xl sm:text-3xl font-bold" style="color: #DC2626;" id="expired-memberships">0</p>
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
                                    <p class="text-2xl sm:text-3xl font-bold" style="color: #8B5CF6;" id="unknown-cards">0</p>
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
                        <!-- Content will be dynamically populated by JavaScript -->
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
            console.log('🚀 RFID Monitor initialized');
            
            // Load initial data
            loadDashboardStats();
            loadRfidLogs();
            checkRfidStatus();
            
            // Setup refresh button
            setupRefreshButton();
            
            // Initialize active members list
            loadActiveMembers();
            
            // Start automatic data refresh
            startDataRefresh();
            
            // Show welcome notification
            setTimeout(() => {
                showNotification('RFID Monitor initialized with 1-second real-time updates', 'success');
            }, 1000);
        });

        // Load dashboard statistics
        function loadDashboardStats() {
            console.log('🔄 Loading dashboard stats...');
            
            try {
                fetch('{{ route("rfid.dashboard-stats") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                cache: 'no-cache'
            })
                .then(response => {
                    console.log('📡 Dashboard stats response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                console.log('📊 Dashboard stats data received:', data);
                if (data.success) {
                    const stats = data.stats;
                    document.getElementById('todays-checkins').textContent = stats.today_checkins || '0';
                    document.getElementById('expired-memberships').textContent = stats.expired_memberships || '0';
                    document.getElementById('unknown-cards').textContent = stats.unknown_cards || '0';
                    console.log('✅ Dashboard stats updated:', stats);
                } else {
                    console.log('❌ Dashboard stats failed:', data.message);
                    // Set to 0 if call fails
                    document.getElementById('todays-checkins').textContent = '0';
                    document.getElementById('expired-memberships').textContent = '0';
                    document.getElementById('unknown-cards').textContent = '0';
                }
                })
                .catch(error => {
                console.error('❌ Error loading dashboard stats:', error);
                // Set to 0 on error
                document.getElementById('todays-checkins').textContent = '0';
                document.getElementById('expired-memberships').textContent = '0';
                document.getElementById('unknown-cards').textContent = '0';
            });
            } catch (error) {
                console.error('❌ Error in loadDashboardStats function:', error);
                // Set to 0 on error
                document.getElementById('todays-checkins').textContent = '0';
                document.getElementById('expired-memberships').textContent = '0';
                document.getElementById('unknown-cards').textContent = '0';
            }
        }

        // Load RFID logs
        function loadRfidLogs(page = 1) {
            console.log('🔄 Loading RFID logs, page:', page);
            
            try {
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
                        container.innerHTML = `
                            <div class="text-center py-8" style="color: #6B7280;">
                                <div class="text-6xl mb-4">📋</div>
                                <p class="text-lg font-medium mb-2" style="color: #000000;">No RFID activity logs found</p>
                                <p class="text-sm">Activity will appear here when RFID cards are used</p>
                            </div>
                        `;
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
                                            <p class="text-sm sm:text-base font-semibold" style="color: #000000;">${log.member_name || logConfig.memberName || 'Unknown Member'}</p>
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
            } catch (error) {
                console.error('❌ Error in loadRfidLogs function:', error);
                document.getElementById('rfid-logs-list').innerHTML = '<div class="text-center py-8" style="color: #DC2626;"><p>Error loading RFID logs: ' + error.message + '</p></div>';
            }
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
                        updateRfidStatus(true, data.message);
                        
                        // Refresh data after starting
                        setTimeout(() => {
                            loadDashboardStats();
                            loadRfidLogs();
                        }, 2000);
                        
                        showNotification('RFID system started successfully', 'success');
                    } else {
                        console.log('❌ RFID startup response:', data.message);
                        updateRfidStatus(false, data.message);
                            showNotification('Failed to start RFID: ' + data.message, 'error');
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
            button.innerHTML = '<span class="text-lg sm:text-xl">⏳</span><span class="text-sm sm:text-base">Stopping...</span>';
            button.disabled = true;
            
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

        // Real-time data refresh intervals
        function startDataRefresh() {
            console.log('🔄 Starting data refresh intervals...');
            
            // Refresh dashboard stats every 1 second for near real-time updates
            setInterval(loadDashboardStats, 1000);
            
            // Refresh RFID logs every 1 second for near real-time updates
            setInterval(loadRfidLogs, 1000);
            
            // Refresh active members every 1 second for real-time updates
            setInterval(loadActiveMembers, 1000);
            
            // Check RFID status every 10 seconds (reduced from 30 seconds)
            setInterval(checkRfidStatus, 10000);
        }
        
        // Active members state management
        let activeMembersState = {
            lastUpdate: 0,
            currentMembers: [],
            isLoading: false,
            retryCount: 0,
            maxRetries: 3
        };

        // Load active members with debouncing and error handling
        function loadActiveMembers() {
            try {
                const now = Date.now();
                const timeSinceLastUpdate = now - activeMembersState.lastUpdate;
            
            // Debounce: Don't update more than once every 500ms for real-time updates
            if (timeSinceLastUpdate < 500 && activeMembersState.currentMembers.length > 0) {
                console.log('🔄 Active members update debounced');
                return;
            }
            
            // Prevent concurrent requests
            if (activeMembersState.isLoading) {
                console.log('🔄 Active members request already in progress');
                return;
            }
            
            activeMembersState.isLoading = true;
            console.log('🔄 Loading active members...');
            
            fetch('{{ route("rfid.active-members") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                cache: 'no-cache'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                activeMembersState.isLoading = false;
                activeMembersState.retryCount = 0;
                
                if (data.success && Array.isArray(data.members)) {
                    // Only update if data has actually changed
                    const membersChanged = JSON.stringify(data.members) !== JSON.stringify(activeMembersState.currentMembers);
                    
                    if (membersChanged) {
                        console.log('🔄 Active members data changed, updating UI');
                        activeMembersState.currentMembers = data.members;
                        activeMembersState.lastUpdate = now;
                        updateActiveMembersList(data.members);
                } else {
                        console.log('🔄 Active members data unchanged, skipping UI update');
                    }
                } else {
                    console.error('Failed to load active members:', data.message || 'Invalid response format');
                    // Don't clear existing data on error, just log it
                }
            })
            .catch(error => {
                activeMembersState.isLoading = false;
                console.error('Error loading active members:', error);
                
                // Retry logic with exponential backoff
                if (activeMembersState.retryCount < activeMembersState.maxRetries) {
                    activeMembersState.retryCount++;
                    const retryDelay = Math.pow(2, activeMembersState.retryCount) * 1000; // 2s, 4s, 8s
                    console.log(`🔄 Retrying active members request in ${retryDelay}ms (attempt ${activeMembersState.retryCount})`);
                    
                    setTimeout(() => {
                        loadActiveMembers();
                    }, retryDelay);
                } else {
                    console.error('🔄 Max retries reached for active members request');
                    // Show error notification but don't clear existing data
                    showNotification('Failed to update active members list', 'error');
                }
            });
            } catch (error) {
                console.error('❌ Error in loadActiveMembers function:', error);
                // Don't show notification for function errors to avoid spam
            }
        }

        // Update active members list in the UI with smooth transitions
        function updateActiveMembersList(members) {
            try {
                const activeMembersList = document.getElementById('active-members-list');
                
                if (!activeMembersList) return;
            
            // Check if we need to update at all
            const currentCount = activeMembersList.children.length;
            const newCount = members ? members.length : 0;
            
            // If count is the same and we have members, check if content is actually different
            if (currentCount === newCount && newCount > 0) {
                const currentContent = activeMembersList.innerHTML;
                const newContent = members.map(member => createActiveMemberElement(member).outerHTML).join('');
                
                if (currentContent === newContent) {
                    console.log('🔄 Active members UI content unchanged, skipping update');
                    return;
                }
            }
            
            // Add loading state
            activeMembersList.style.opacity = '0.7';
            activeMembersList.style.transition = 'opacity 0.3s ease';
            
            // Use requestAnimationFrame for smooth updates
            requestAnimationFrame(() => {
                // Clear existing content
                activeMembersList.innerHTML = '';
                
                if (members && members.length > 0) {
                    
                    // Add each active member with staggered animation
                    members.forEach((member, index) => {
                        const memberElement = createActiveMemberElement(member);
                        memberElement.style.opacity = '0';
                        memberElement.style.transform = 'translateY(10px)';
                        memberElement.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        
                        activeMembersList.appendChild(memberElement);
                        
                        // Stagger the animation
                        setTimeout(() => {
                            memberElement.style.opacity = '1';
                            memberElement.style.transform = 'translateY(0)';
                        }, index * 100);
                    });
                } else {
                    // Show "no active members" message
                    const noMembersElement = document.createElement('div');
                    noMembersElement.className = 'text-center py-8';
                    noMembersElement.style.color = '#6B7280';
                    noMembersElement.style.opacity = '0';
                    noMembersElement.style.transition = 'opacity 0.3s ease';
                    noMembersElement.innerHTML = `
                        <div class="text-6xl mb-4">👥</div>
                        <p class="text-lg font-medium mb-2" style="color: #000000;">No active members currently</p>
                    `;
                    activeMembersList.appendChild(noMembersElement);
                    
                    // Animate in
                    setTimeout(() => {
                        noMembersElement.style.opacity = '1';
                    }, 100);
                    
                }
                
                // Restore full opacity
                setTimeout(() => {
                    activeMembersList.style.opacity = '1';
                }, 300);
                
            });
            } catch (error) {
                console.error('❌ Error in updateActiveMembersList function:', error);
            }
        }

        // Create active member element
        function createActiveMemberElement(member) {
            const memberDiv = document.createElement('div');
            memberDiv.className = 'bg-white border rounded-lg p-4 sm:p-6';
            memberDiv.style.borderColor = '#E5E7EB';
            memberDiv.style.boxShadow = '0 2px 4px rgba(0,0,0,0.05)';
            
            // Calculate session duration
            const checkInTime = new Date(member.check_in_time);
            const now = new Date();
            const durationMs = now - checkInTime;
            const hours = Math.floor(durationMs / (1000 * 60 * 60));
            const minutes = Math.floor((durationMs % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((durationMs % (1000 * 60)) / 1000);
            
            let durationText = '';
            if (hours > 0) {
                durationText = `${hours}h ${minutes}m ${seconds}s`;
            } else if (minutes > 0) {
                durationText = `${minutes}m ${seconds}s`;
            } else {
                durationText = `${seconds}s`;
            }
            
            memberDiv.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 text-lg">👤</span>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-semibold text-gray-900">${member.full_name || member.first_name + ' ' + member.last_name}</h3>
                            <p class="text-xs sm:text-sm text-gray-500">Member #${member.member_number}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs sm:text-sm text-gray-500">Check-in:</p>
                        <p class="text-xs sm:text-sm font-medium text-gray-900">${checkInTime.toLocaleTimeString()}</p>
                        <p class="text-xs text-green-600 font-medium">${durationText}</p>
                    </div>
                </div>
            `;
            
            return memberDiv;
        }
        
        // Manual refresh function for immediate updates
        function manualRefresh() {
            console.log('🔄 Manual refresh triggered');
            
            loadDashboardStats();
            loadRfidLogs();
            loadActiveMembers();
            checkRfidStatus();
            showNotification('Dashboard refreshed', 'success');
        }

        // Add event listener for refresh button
        function setupRefreshButton() {
            const refreshButton = document.getElementById('refresh-logs-btn');
            if (refreshButton) {
                refreshButton.addEventListener('click', function() {
                    manualRefresh();
                });
            }
        }

        // Show Brave-specific instructions
        function showBraveInstructions() {
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

        // Notification System
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            const bgColor = type === 'error' ? '#DC2626' : type === 'success' ? '#059669' : type === 'warning' ? '#D97706' : '#2563EB';
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
    </script>


</x-layout>
