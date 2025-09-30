// Real-time Clock and Member Expiration Tracking
class RealtimeManager {
    constructor() {
        this.clockInterval = null;
        this.expirationInterval = null;
        this.tapInterval = null;
        this.init();
    }

    init() {
        this.startClock();
        this.startExpirationTracking();
        this.startTapTracking();
    }

    // Real-time Clock (12-hour format)
    startClock() {
        const updateClock = () => {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            
            const clockElement = document.getElementById('realtime-clock');
            if (clockElement) {
                clockElement.textContent = timeString;
            }
        };

        // Update immediately
        updateClock();
        
        // Update every second
        this.clockInterval = setInterval(updateClock, 1000);
    }

    // Member Expiration Tracking
    startExpirationTracking() {
        const updateExpirations = () => {
            const expirationElements = document.querySelectorAll('[data-expiration-date]');
            
            expirationElements.forEach(element => {
                const expirationDate = new Date(element.dataset.expirationDate);
                const today = new Date();
                const timeDiff = expirationDate.getTime() - today.getTime();
                const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                let statusText = '';
                let statusClass = '';
                
                if (daysDiff > 30) {
                    statusText = `Expires in ${daysDiff} days`;
                    statusClass = 'text-green-600 bg-green-100';
                } else if (daysDiff >= 7) {
                    statusText = `Expires in ${daysDiff} days`;
                    statusClass = 'text-yellow-600 bg-yellow-100';
                } else if (daysDiff > 0) {
                    statusText = `Expires in ${daysDiff} days`;
                    statusClass = 'text-red-600 bg-red-100';
                } else if (daysDiff === 0) {
                    statusText = 'Expires today';
                    statusClass = 'text-red-600 bg-red-100';
                } else {
                    statusText = `Expired ${Math.abs(daysDiff)} days ago`;
                    statusClass = 'text-red-600 bg-red-100';
                }
                
                element.innerHTML = `
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">
                        ${statusText}
                    </span>
                `;
            });
        };

        // Update immediately
        updateExpirations();
        
        // Update every minute
        this.expirationInterval = setInterval(updateExpirations, 60000);
    }

    // Real-time Tap In/Out System
    startTapTracking() {
        const updateTaps = () => {
            // Update active members
            this.updateActiveMembers();
            
            // Update tap history
            this.updateTapHistory();
            
            // Update dashboard stats
            this.updateDashboardStats();
        };

        // Update immediately
        updateTaps();
        
        // Update every 5 seconds
        this.tapInterval = setInterval(updateTaps, 5000);
    }

    // Update Active Members
    async updateActiveMembers() {
        try {
            const response = await fetch('/rfid/active-members');
            if (response.ok) {
                const data = await response.json();
                const container = document.getElementById('active-members-list');
                
                if (container) {
                    if (data.active_members && data.active_members.length > 0) {
                        container.innerHTML = data.active_members.map(member => `
                            <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">${member.name}</div>
                                        <div class="text-sm text-gray-500">${member.plan} - ${member.uid}</div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    ${member.check_in_time}
                                </div>
                            </div>
                        `).join('');
                    } else {
                        container.innerHTML = `
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <p>No active members currently</p>
                            </div>
                        `;
                    }
                }
            }
        } catch (error) {
            console.error('Error updating active members:', error);
        }
    }

    // Update Tap History
    async updateTapHistory() {
        try {
            const response = await fetch('/rfid/logs');
            if (response.ok) {
                const data = await response.json();
                const container = document.getElementById('rfid-logs-list');
                
                if (container && data.logs) {
                    container.innerHTML = data.logs.map(log => `
                        <div class="flex items-center justify-between p-3 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 ${log.action === 'check-in' ? 'bg-green-100' : 'bg-red-100'} rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 ${log.action === 'check-in' ? 'text-green-600' : 'text-red-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">${log.member_name}</div>
                                    <div class="text-sm text-gray-500">${log.uid} - ${log.plan}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium ${log.action === 'check-in' ? 'text-green-600' : 'text-red-600'}">
                                    ${log.action === 'check-in' ? 'Checked In' : 'Checked Out'}
                                </div>
                                <div class="text-xs text-gray-500">${log.timestamp}</div>
                            </div>
                        </div>
                    `).join('');
                }
            }
        } catch (error) {
            console.error('Error updating tap history:', error);
        }
    }

    // Update Dashboard Stats
    async updateDashboardStats() {
        try {
            const response = await fetch('/rfid/dashboard-stats');
            if (response.ok) {
                const data = await response.json();
                
                // Update today's check-ins
                const checkinsElement = document.getElementById('todays-checkins');
                if (checkinsElement) {
                    checkinsElement.textContent = data.todays_checkins || 0;
                }
                
                // Update expired memberships
                const expiredElement = document.getElementById('expired-memberships');
                if (expiredElement) {
                    expiredElement.textContent = data.expired_memberships || 0;
                }
                
                // Update unknown cards
                const unknownElement = document.getElementById('unknown-cards');
                if (unknownElement) {
                    unknownElement.textContent = data.unknown_cards || 0;
                }
            }
        } catch (error) {
            console.error('Error updating dashboard stats:', error);
        }
    }

    // Format time for display
    formatTime(date) {
        return date.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
    }

    // Calculate days until expiration
    calculateDaysUntilExpiration(expirationDate) {
        const today = new Date();
        const expDate = new Date(expirationDate);
        const timeDiff = expDate.getTime() - today.getTime();
        return Math.ceil(timeDiff / (1000 * 3600 * 24));
    }

    // Get expiration status class
    getExpirationStatusClass(days) {
        if (days > 30) return 'text-green-600 bg-green-100';
        if (days >= 7) return 'text-yellow-600 bg-yellow-100';
        return 'text-red-600 bg-red-100';
    }

    // Cleanup intervals
    destroy() {
        if (this.clockInterval) clearInterval(this.clockInterval);
        if (this.expirationInterval) clearInterval(this.expirationInterval);
        if (this.tapInterval) clearInterval(this.tapInterval);
    }
}

// Initialize real-time manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.realtimeManager = new RealtimeManager();
});

// Cleanup when page unloads
window.addEventListener('beforeunload', function() {
    if (window.realtimeManager) {
        window.realtimeManager.destroy();
    }
});
