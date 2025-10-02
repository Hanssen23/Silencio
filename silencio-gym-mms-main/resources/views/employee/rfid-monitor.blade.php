<x-layout>
    <x-nav-employee></x-nav-employee>
    <div class="flex-1 bg-white">
        <x-topbar>RFID Monitor</x-topbar>

        <div class="bg-white p-6">
            <!-- Metrics Section -->
            <div class="mb-8">
                <div class="bg-white rounded-lg border p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <h2 class="text-2xl font-bold mb-6" style="color: #1E40AF;">Today's Metrics</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white border rounded-lg p-6" style="border-color: #059669; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium mb-2" style="color: #6B7280;">Today's Check-ins</p>
                                    <p class="text-3xl font-bold" style="color: #059669;" id="today-checkins">-</p>
                                </div>
                                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #059669;">
                                    <span class="text-2xl text-white">✅</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-6" style="border-color: #DC2626; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium mb-2" style="color: #6B7280;">Failed Attempts</p>
                                    <p class="text-3xl font-bold" style="color: #DC2626;" id="failed-attempts">-</p>
                                </div>
                                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #DC2626;">
                                    <span class="text-2xl text-white">❌</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border rounded-lg p-6" style="border-color: #8B5CF6; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium mb-2" style="color: #6B7280;">Unknown Cards</p>
                                    <p class="text-3xl font-bold" style="color: #8B5CF6;" id="unknown-cards">-</p>
                                </div>
                                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #8B5CF6;">
                                    <span class="text-2xl text-white">❓</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RFID Control Panel -->
            <div class="mb-8">
                <div class="bg-white rounded-lg border p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold" style="color: #1E40AF;">RFID Control</h2>
                        <div class="flex items-center gap-3">
                            <div id="rfid-control-status-indicator" class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span id="rfid-control-status-text" class="text-sm font-medium" style="color: #DC2626;">System Offline</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-center gap-6 flex-wrap">
                        <button onclick="startRfidSystem()" id="start-rfid-control-btn" 
                                class="px-8 py-4 text-white text-lg rounded-lg transition-colors flex items-center gap-3" 
                                style="background-color: #059669;" 
                                onmouseover="this.style.backgroundColor='#047857'" 
                                onmouseout="this.style.backgroundColor='#059669'">
                            <span class="text-xl">▶️</span>
                            Start RFID
                        </button>
                        
                        <button onclick="stopRfidSystem()" id="stop-rfid-control-btn" 
                                class="px-8 py-4 text-white text-lg rounded-lg transition-colors flex items-center gap-3" 
                                style="background-color: #DC2626;" 
                                onmouseover="this.style.backgroundColor='#B91C1C'" 
                                onmouseout="this.style.backgroundColor='#DC2626'">
                            <span class="text-xl">⏹️</span>
                            Stop RFID
                        </button>
                        
                    </div>
                </div>
            </div>

            <!-- Currently Active Members -->
            <div class="mb-8">
                <div class="bg-white rounded-lg border p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold" style="color: #1E40AF;">Currently Active Members</h2>
                    </div>
                    <div id="active-members-list" class="space-y-3">
                        <!-- Content will be dynamically populated by JavaScript -->
                    </div>
                    
                </div>
            </div>

            <!-- Recent RFID Activity -->
            <div class="mb-8">
                <div class="bg-white rounded-lg border p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold" style="color: #1E40AF;">Recent RFID Activity</h2>
                        <div class="flex items-center gap-4">
                            <select id="log-filter" 
                                    class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                    style="border-color: #E5E7EB;">
                                <option value="">All Actions</option>
                                <option value="check_in">Check-ins</option>
                                <option value="check_out">Check-outs</option>
                                <option value="unknown_card">Unknown Cards</option>
                                <option value="expired_membership">Expired Memberships</option>
                            </select>
                            <button onclick="refreshLogs()" 
                                    class="px-4 py-2 text-white text-sm rounded-lg transition-colors flex items-center gap-2" 
                                    style="background-color: #2563EB;" 
                                    onmouseover="this.style.backgroundColor='#1D4ED8'" 
                                    onmouseout="this.style.backgroundColor='#2563EB'">
                                <span class="text-lg">🔄</span>
                                Refresh
                            </button>
                        </div>
                    </div>
                    
                    <div id="rfid-logs-list" class="space-y-2">
                        <div class="text-center py-8" style="color: #6B7280;">
                            <p>Loading RFID logs...</p>
                        </div>
                    </div>
                    
                    <!-- Pagination Controls -->
                    <div id="rfid-logs-pagination" class="mt-6 flex items-center justify-between">
                        <div class="text-sm" style="color: #6B7280;">
                            <span id="logs-info">Showing 0 to 0 of 0 results</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button id="prev-page" onclick="changePage(-1)" 
                                    class="px-4 py-2 text-sm font-medium border rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed" 
                                    style="border-color: #E5E7EB; color: #6B7280;" 
                                    onmouseover="this.style.backgroundColor='#F3F4F6'" 
                                    onmouseout="this.style.backgroundColor='transparent'">
                                Previous
                            </button>
                            <span id="page-info" class="px-4 py-2 text-sm" style="color: #6B7280;">Page 1</span>
                            <button id="next-page" onclick="changePage(1)" 
                                    class="px-4 py-2 text-sm font-medium border rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed" 
                                    style="border-color: #E5E7EB; color: #6B7280;" 
                                    onmouseover="this.style.backgroundColor='#F3F4F6'" 
                                    onmouseout="this.style.backgroundColor='transparent'">
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
            loadRfidLogs();
            // Note: loadActiveMembers() completely removed - section is now static
            
            // Auto-refresh every 10 seconds for real-time updates (active members removed - now event-driven only)
            setInterval(function() {
                console.log('Auto-refreshing RFID Monitor data...');
                loadDashboardStats();
                loadRfidLogs(currentPage); // Maintain current page during auto-refresh
                // Note: loadActiveMembers() removed - section is now static
            }, 10000);
            
            // Check RFID status every 10 seconds
            setInterval(function() {
                checkRfidStatus();
            }, 10000);
            
        });

        // Load dashboard statistics
        function loadDashboardStats() {
            console.log('🔄 Loading dashboard stats...');
            fetch('{{ route("employee.dashboard.stats") }}')
                .then(response => {
                    console.log('📡 Dashboard stats response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📊 Dashboard stats loaded:', data);
                    document.getElementById('current-active-count').textContent = data.current_active_members;
                    document.getElementById('today-checkins').textContent = data.today_attendance;
                    document.getElementById('failed-attempts').textContent = data.failed_rfid_today;
                })
                .catch(error => {
                    console.error('Error loading dashboard stats:', error);
                    // Show error indicator
                    document.getElementById('current-active-count').textContent = 'Error';
                    document.getElementById('today-checkins').textContent = 'Error';
                    document.getElementById('failed-attempts').textContent = 'Error';
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
                            <div class="flex items-center p-4 rounded-lg border-l-4" 
                                 style="background-color: ${index % 2 === 0 ? '#FFFFFF' : '#F9FAFB'}; border-left-color: ${logConfig.borderColor}; border-color: #E5E7EB;">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: ${logConfig.bgColor};">
                                        <span class="text-xl text-white">${logConfig.icon}</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="font-semibold" style="color: #000000;">${log.member_name || logConfig.memberName || 'Unknown Member'}</p>
                                            <span class="text-sm px-2 py-1 rounded-full text-white" style="background-color: ${logConfig.badgeColor};">
                                                ${logConfig.actionText}
                                            </span>
                                        </div>
                                        <p class="text-sm" style="color: #6B7280;">${logConfig.description}</p>
                                        <p class="text-xs" style="color: #6B7280;">Card: ${log.card_uid}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium" style="color: #000000;">${formattedTime}</p>
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

        // Refresh functions

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
            button.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Starting...';
            button.disabled = true;
            
            fetch('{{ route("employee.rfid.start") }}')
                .then(response => response.json())
                .then(data => {
                    console.log('📊 RFID start response:', data);
                    
                    if (data.success) {
                        console.log('✅ RFID system started successfully');
                        // Immediately update status to online
                        updateRfidStatus(true, data.message);
                        
                        // Refresh data after starting
                        setTimeout(() => {
                            loadDashboardStats();
                            loadRfidLogs();
                        }, 2000);
                    } else {
                        console.log('❌ Failed to start RFID system:', data.message);
                        updateRfidStatus(false, data.message);
                    }
                })
                .catch(error => {
                    console.error('❌ Error starting RFID system:', error);
                    updateRfidStatus(false, 'Error starting RFID system');
                })
                .finally(() => {
                    // Restore button
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }

        
        function checkRfidStatus() {
            fetch('{{ route("employee.rfid.status") }}')
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
            fetch('{{ route("employee.rfid.stop") }}')
                .then(response => response.json())
                .then(data => {
                    console.log('📊 RFID stop response:', data);
                    
                    if (data.success) {
                        console.log('✅ RFID system stopped successfully');
                        updateRfidStatus(false, data.message);
                    } else {
                        console.log('❌ Failed to stop RFID system:', data.message);
                        updateRfidStatus(true, data.message);
                    }
                })
                .catch(error => {
                    console.error('❌ Error stopping RFID system:', error);
                    updateRfidStatus(false, 'Error stopping RFID system');
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
                controlIndicator.className = 'w-3 h-3 bg-green-500 rounded-full animate-pulse';
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

        // Note: loadActiveMembers function completely removed - section is now static

        // Note: refreshActiveMembers function removed - section is now completely static

        // Format time with date
        function formatTimeWithDate(date) {
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            
            if (diffMins < 1) {
                return 'Just now';
            } else if (diffMins < 60) {
                return `${diffMins}m ago`;
            } else if (diffHours < 24) {
                return `${diffHours}h ago`;
            } else {
                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            }
        }

        // NFC Integration Script
        let nfcSupported = false;
        let nfcReader = null;

        document.addEventListener('DOMContentLoaded', function() {
            checkNfcSupport();
            setupMobileNfcComponent();
            loadActiveMembers();
        });

        // Browser detection
        async function detectBrowser() {
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

        async function checkNfcSupport() {
            // Always show the NFC button, but check support for functionality
            try {
                const browser = await detectBrowser();
                console.log('🌐 Browser detected:', browser);
                
                if ('NDEFReader' in window) {
                    nfcSupported = true;
                    console.log('✅ Web NFC is supported');
                    
                    // Check for Brave-specific issues
                    if (browser === 'brave') {
                        console.log('🦁 Brave browser detected - checking NFC configuration');
                        
                        // Check if we're on HTTPS
                        if (location.protocol !== 'https:') {
                            console.log('⚠️ Brave requires HTTPS for NFC');
                            showNotification('NFC requires HTTPS in Brave browser. Please use HTTPS or enable NFC in brave://flags', 'warning');
                        }
                    }
                } else {
                    nfcSupported = false;
                    console.log('❌ Web NFC is not supported');
                    
                    if (browser === 'brave') {
                        console.log('🦁 Brave browser - NFC may be disabled in flags');
                        showNotification('NFC is disabled in Brave. Enable it in brave://flags/#enable-web-nfc', 'info');
                    }
                }
            } catch (error) {
                nfcSupported = false;
                console.log('❌ Web NFC check failed:', error);
            }
            
            // Always show the main NFC button (let users try it)
            const nfcButton = document.getElementById('nfc-checkin-btn');
            if (nfcButton) {
                nfcButton.style.display = 'block';
                nfcButton.style.visibility = 'visible';
            }
            
            // Show floating button on mobile devices
            if (window.innerWidth <= 768) {
                const floatingBtn = document.getElementById('floating-nfc-btn');
                if (floatingBtn) {
                    floatingBtn.classList.remove('hidden');
                }
            }
        }

        async function startNfcCheckIn() {
            try {
                const browser = await detectBrowser();
                console.log('🌐 Starting NFC check-in on:', browser);
                
                if (!nfcSupported) {
                    if (browser === 'brave') {
                        showNotification('NFC is disabled in Brave. Enable it in brave://flags/#enable-web-nfc', 'error');
                    } else {
                        showNotification('NFC is not supported on this device', 'error');
                    }
                    return;
                }

                const button = document.getElementById('nfc-checkin-btn');
                if (!button) {
                    console.error('NFC button not found');
                    return;
                }
                
                const originalText = button.innerHTML;
                button.innerHTML = '<span class="text-xl">⏳</span> Initializing NFC...';
                button.disabled = true;

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
                    
                    nfcReader = new NDEFReader();
                    await nfcReader.scan();
                    showNotification('NFC ready! Tap your phone to an NFC tag', 'success');
                    nfcReader.addEventListener('reading', handleNfcRead);
                    nfcReader.addEventListener('readingerror', handleNfcError);
                } catch (error) {
                    console.error('NFC error:', error);
                    
                    // Brave-specific error handling
                    if (browser === 'brave') {
                        if (error.message.includes('HTTPS')) {
                            showNotification('Brave requires HTTPS for NFC. Please use HTTPS or enable NFC in brave://flags', 'error');
                        } else if (error.message.includes('permission')) {
                            showNotification('NFC permission denied in Brave. Check brave://flags/#enable-web-nfc', 'error');
                        } else {
                            showNotification('NFC error in Brave: ' + error.message + '. Check brave://flags/#enable-web-nfc', 'error');
                        }
                    } else {
                        showNotification('NFC initialization failed: ' + error.message, 'error');
                    }
                    
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            } catch (error) {
                console.error('❌ Error in startNfcCheckIn function:', error);
                showNotification('Error initializing NFC: ' + error.message, 'error');
            }
        }

        function handleNfcRead(event) {
            let uid = '';
            if (event.serialNumber) {
                uid = Array.from(event.serialNumber).map(byte => byte.toString(16).padStart(2, '0')).join('').toUpperCase();
            } else {
                uid = 'NFC' + Date.now().toString(16).substr(-8).toUpperCase();
            }
            sendNfcToRfidApi(uid);
        }

        function handleNfcError(error) {
            console.error('NFC reading error:', error);
            showNotification('NFC reading failed', 'error');
        }

        async function sendNfcToRfidApi(uid) {
            try {
                const response = await fetch('{{ route("rfid.tap") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        card_uid: uid,
                        device_id: 'nfc_mobile',
                        source: 'nfc'
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    showNotification('NFC check-in successful!', 'success');
                    loadDashboardStats();
                    loadRfidLogs();
                    loadActiveMembers();
                } else {
                    showNotification('NFC check-in failed: ' + (data.message || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error('API error:', error);
                showNotification('Failed to process NFC check-in', 'error');
            }
        }

        // Load active members
        function loadActiveMembers() {
            console.log('🔄 Loading active members...');
            
            fetch('{{ route("rfid.active-members") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateActiveMembersList(data.members);
                    } else {
                        console.error('Failed to load active members:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading active members:', error);
                });
        }

        // Update active members list in the UI
        function updateActiveMembersList(members) {
            const activeMembersList = document.getElementById('active-members-list');
            const mobileNfcComponent = document.getElementById('mobile-nfc-component');
            
            if (!activeMembersList) return;
            
            // Clear existing content
            activeMembersList.innerHTML = '';
            
            if (members && members.length > 0) {
                // Hide mobile NFC component when there are active members
                if (mobileNfcComponent) {
                    mobileNfcComponent.classList.add('hidden');
                }
                
                // Add each active member
                members.forEach(member => {
                    const memberElement = createActiveMemberElement(member);
                    activeMembersList.appendChild(memberElement);
                });
            } else {
                // Show "no active members" message
                const noMembersElement = document.createElement('div');
                noMembersElement.className = 'text-center py-8';
                noMembersElement.style.color = '#6B7280';
                noMembersElement.innerHTML = `
                    <div class="text-6xl mb-4">👥</div>
                    <p class="text-lg font-medium mb-2" style="color: #000000;">No active members currently</p>
                `;
                activeMembersList.appendChild(noMembersElement);
                
                // Show mobile NFC component when no active members and NFC is supported
                if (mobileNfcComponent && nfcSupported) {
                    mobileNfcComponent.classList.remove('hidden');
                }
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

        // Setup mobile NFC component visibility
        function setupMobileNfcComponent() {
            const activeMembersList = document.getElementById('active-members-list');
            const mobileNfcComponent = document.getElementById('mobile-nfc-component');
            
            // Show mobile NFC component when no active members and NFC is supported
            const hasActiveMembers = activeMembersList.children.length > 0 && 
                !activeMembersList.querySelector('.text-center.py-8');
            
            if (nfcSupported && !hasActiveMembers) {
                mobileNfcComponent.classList.remove('hidden');
            } else {
                mobileNfcComponent.classList.add('hidden');
            }
        }

        // Load dashboard statistics
        function loadDashboardStats() {
            fetch('{{ route("rfid.dashboard-stats") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('today-checkins').textContent = data.stats.today_checkins;
                        document.getElementById('failed-attempts').textContent = data.stats.expired_memberships;
                        document.getElementById('unknown-cards').textContent = data.stats.unknown_cards;
                    }
                })
                .catch(error => {
                    console.error('Error loading dashboard stats:', error);
                });
        }

        // Load RFID logs
        function loadRfidLogs() {
            fetch('{{ route("rfid.logs") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateRfidLogs(data.logs);
                    }
                })
                .catch(error => {
                    console.error('Error loading RFID logs:', error);
                });
        }

        // Update RFID logs in the UI
        function updateRfidLogs(logs) {
            const logsList = document.getElementById('rfid-logs-list');
            if (!logsList) return;
            
            logsList.innerHTML = '';
            
            if (logs && logs.length > 0) {
                logs.forEach(log => {
                    const logElement = createLogElement(log);
                    logsList.appendChild(logElement);
                });
            } else {
                const noLogsElement = document.createElement('div');
                noLogsElement.className = 'text-center py-8 text-gray-500';
                noLogsElement.textContent = 'No RFID activity yet';
                logsList.appendChild(noLogsElement);
            }
        }

        // Create log element
        function createLogElement(log) {
            const logDiv = document.createElement('div');
            logDiv.className = 'border-b border-gray-200 py-3 last:border-b-0';
            
            const timestamp = new Date(log.timestamp);
            const timeAgo = formatTimeWithDate(timestamp);
            
            logDiv.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">${log.message}</p>
                        <p class="text-xs text-gray-500">${log.card_uid} • ${log.device_id}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">${timeAgo}</p>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(log.status)}">
                            ${log.status}
                        </span>
                    </div>
                </div>
            `;
            
            return logDiv;
        }

        // Get status color class
        function getStatusColor(status) {
            switch (status) {
                case 'success': return 'bg-green-100 text-green-800';
                case 'error': return 'bg-red-100 text-red-800';
                case 'warning': return 'bg-yellow-100 text-yellow-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm`;
            
            switch (type) {
                case 'success':
                    notification.style.backgroundColor = '#059669';
                    notification.style.color = 'white';
                    break;
                case 'error':
                    notification.style.backgroundColor = '#DC2626';
                    notification.style.color = 'white';
                    break;
                case 'warning':
                    notification.style.backgroundColor = '#D97706';
                    notification.style.color = 'white';
                    break;
                default:
                    notification.style.backgroundColor = '#3B82F6';
                    notification.style.color = 'white';
            }
            
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 3000);
        }

    </script>

</x-layout>
