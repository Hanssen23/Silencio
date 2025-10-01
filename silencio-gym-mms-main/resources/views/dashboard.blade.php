<x-layout>
    @if(auth()->user()->role === 'employee')
        <x-nav-employee></x-nav-employee>
    @else
        <x-nav></x-nav>
    @endif
    <div class="flex-1 bg-gray-100">
        <x-topbar>Dashboard</x-topbar>

        <div class="bg-gray-100 p-4 sm:p-6">
            <!-- Clean, Minimal Overview Section -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 lg:p-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Overview</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                        <!-- Current Active Members -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 sm:p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs sm:text-sm font-medium text-blue-700 mb-1">Currently Active</p>
                                    <p id="current-active-members-count" class="text-2xl sm:text-3xl font-bold text-blue-900">{{ $currentActiveMembersCount }}</p>
                                    <p class="text-xs text-blue-600 mt-1">Members logged in</p>
                                </div>
                                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-200 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Today's Attendance -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4 sm:p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs sm:text-sm font-medium text-green-700 mb-1">Today's Attendance</p>
                                    <p id="today-attendance-count" class="text-2xl sm:text-3xl font-bold text-green-900">{{ $todayAttendance }}</p>
                                    <p class="text-xs text-green-600 mt-1">Check-ins today</p>
                                </div>
                                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-green-200 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Weekly Revenue -->
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl p-4 sm:p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs sm:text-sm font-medium text-yellow-700 mb-1">Weekly Revenue</p>
                                    <p class="text-2xl sm:text-3xl font-bold text-yellow-900">₱{{ number_format($thisWeekRevenue, 0) }}</p>
                                    <p class="text-xs text-yellow-600 mt-1">This week</p>
                                </div>
                                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-yellow-200 rounded-full flex items-center justify-center">
                                    <span class="text-xl sm:text-2xl font-bold text-yellow-700">₱</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics & Reports Section -->
            <div class="mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:p-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6">Analytics & Reports</h2>
                    
                    <!-- Performance Metrics Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
                        <!-- Total Members -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 sm:p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs sm:text-sm font-medium text-blue-700 mb-1">Total Members</p>
                                    <p class="text-xl sm:text-2xl font-bold text-blue-900">{{ $totalActiveMembersCount }}</p>
                                    <p class="text-xs text-blue-600 mt-1">Active accounts</p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-200 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Weekly Attendance -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4 sm:p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs sm:text-sm font-medium text-green-700 mb-1">This Week</p>
                                    <p class="text-xl sm:text-2xl font-bold text-green-900">{{ $thisWeekAttendance }}</p>
                                    <p class="text-xs text-green-600 mt-1">Check-ins</p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-200 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Expiring Memberships -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-4 sm:p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs sm:text-sm font-medium text-red-700 mb-1">Expiring</p>
                                    <p class="text-xl sm:text-2xl font-bold text-red-900">{{ $expiringMembershipsCount }}</p>
                                    <p class="text-xs text-red-600 mt-1">This week: {{ $expiringMembershipsThisWeek ?? 0 }}</p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-200 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Weekly Attendance Chart -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Weekly Attendance Trend</h3>
                                <div class="flex items-center gap-2">
                                    <!-- Calendar-style date picker -->
                                    <div class="relative">
                                        <button onclick="toggleAttendanceCalendar()" class="flex items-center gap-2 px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span id="attendanceDateText">Last 7 days</span>
                                        </button>
                                        <div id="attendanceCalendar" class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-10 hidden">
                                            <div class="p-4">
                                                <!-- Calendar Header -->
                                                <div class="flex items-center justify-between mb-4">
                                                    <button onclick="navigateAttendanceMonth(-1)" class="p-2 hover:bg-gray-100 rounded-full">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                        </svg>
                                                    </button>
                                                    <div class="flex items-center gap-2">
                                                        <span id="attendanceCalendarMonth" class="text-lg font-semibold text-gray-900">May</span>
                                                        <span id="attendanceCalendarYear" class="text-lg font-semibold text-gray-900">2024</span>
                                                    </div>
                                                    <button onclick="navigateAttendanceMonth(1)" class="p-2 hover:bg-gray-100 rounded-full">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                
                                                <!-- Calendar Grid -->
                                                <div class="calendar-header">
                                                    <div class="calendar-header-day">Su</div>
                                                    <div class="calendar-header-day">Mo</div>
                                                    <div class="calendar-header-day">Tu</div>
                                                    <div class="calendar-header-day">We</div>
                                                    <div class="calendar-header-day">Th</div>
                                                    <div class="calendar-header-day">Fr</div>
                                                    <div class="calendar-header-day">Sa</div>
                                                </div>
                                                
                                                <div id="attendanceCalendarDays" class="calendar-grid">
                                                    <!-- Calendar days will be populated by JavaScript -->
                                                </div>
                                                
                                                <!-- Quick Actions -->
                                                <div class="mt-4 pt-4 border-t border-gray-200">
                                                    <div class="grid grid-cols-3 gap-2">
                                                        <button onclick="selectAttendanceQuickPeriod(7)" class="px-3 py-2 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Last 7 days</button>
                                                        <button onclick="selectAttendanceQuickPeriod(14)" class="px-3 py-2 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200">Last 14 days</button>
                                                        <button onclick="selectAttendanceQuickPeriod(30)" class="px-3 py-2 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200">Last 30 days</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button onclick="refreshAttendanceChart()" class="text-gray-500 hover:text-gray-700 transition-colors" title="Refresh">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="h-64">
                                <canvas id="weeklyAttendanceChart"></canvas>
                            </div>
                            <div class="mt-2 text-sm text-gray-500 text-center">
                                <span id="attendanceTotal">Total: 0</span> check-ins
                            </div>
                        </div>

                        <!-- Weekly Revenue Chart -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Weekly Revenue Trend</h3>
                                <div class="flex items-center gap-2">
                                    <!-- Calendar-style date picker -->
                                    <div class="relative">
                                        <button onclick="toggleRevenueCalendar()" class="flex items-center gap-2 px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span id="revenueDateText">May 2024</span>
                                        </button>
                                        <div id="revenueCalendar" class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-10 hidden">
                                            <div class="p-4">
                                                <!-- Calendar Header -->
                                                <div class="flex items-center justify-between mb-4">
                                                    <button onclick="navigateRevenueMonth(-1)" class="p-2 hover:bg-gray-100 rounded-full">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                        </svg>
                                                    </button>
                                                    <div class="flex items-center gap-2">
                                                        <span id="revenueCalendarMonth" class="text-lg font-semibold text-gray-900">May</span>
                                                        <span id="revenueCalendarYear" class="text-lg font-semibold text-gray-900">2024</span>
                                                    </div>
                                                    <button onclick="navigateRevenueMonth(1)" class="p-2 hover:bg-gray-100 rounded-full">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                
                                                <!-- Calendar Grid -->
                                                <div class="calendar-header">
                                                    <div class="calendar-header-day">Su</div>
                                                    <div class="calendar-header-day">Mo</div>
                                                    <div class="calendar-header-day">Tu</div>
                                                    <div class="calendar-header-day">We</div>
                                                    <div class="calendar-header-day">Th</div>
                                                    <div class="calendar-header-day">Fr</div>
                                                    <div class="calendar-header-day">Sa</div>
                                                </div>
                                                
                                                <div id="revenueCalendarDays" class="calendar-grid">
                                                    <!-- Calendar days will be populated by JavaScript -->
                                                </div>
                                                
                                                <!-- Quick Actions -->
                                                <div class="mt-4 pt-4 border-t border-gray-200">
                                                    <div class="grid grid-cols-3 gap-2">
                                                        <button onclick="selectRevenueQuickPeriod('current')" class="px-3 py-2 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200">Current Month</button>
                                                        <button onclick="selectRevenueQuickPeriod('previous')" class="px-3 py-2 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Previous Month</button>
                                                        <button onclick="selectRevenueQuickPeriod('year')" class="px-3 py-2 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200">This Year</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button onclick="refreshRevenueChart()" class="text-gray-500 hover:text-gray-700 transition-colors" title="Refresh">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="h-64">
                                <canvas id="weeklyRevenueChart"></canvas>
                            </div>
                            <div class="mt-2 text-sm text-gray-500 text-center">
                                <span id="revenueTotal">Total: ₱0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Calendar Styles -->
    <style>
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }
        
        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 32px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        
        .calendar-day:hover:not(.inactive) {
            background-color: #f3f4f6;
        }
        
        .calendar-day.selected {
            background-color: #fbbf24;
            font-weight: 600;
        }
        
        .calendar-day.today {
            background-color: #dbeafe;
        }
        
        .calendar-day.inactive {
            color: #9ca3af;
            cursor: default;
        }
        
        .calendar-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            margin-bottom: 8px;
        }
        
        .calendar-header-day {
            text-align: center;
            font-weight: 500;
            color: #6b7280;
            padding: 8px 0;
        }
        
        @media (max-width: 640px) {
            .calendar-day {
                min-height: 28px;
                font-size: 0.875rem;
            }
            
            .calendar-header-day {
                font-size: 0.75rem;
                padding: 6px 0;
            }
        }
    </style>

    <script>
        let weeklyChart = null;
        let revenueChart = null;
        let refreshInterval = null;
        
        // Calendar state
        let currentAttendancePeriod = 7;
        let currentRevenueDate = new Date();
        let attendanceCalendarVisible = false;
        let revenueCalendarVisible = false;

        // Initialize charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            initializeCalendars();
            loadRealTimeData();
            
            // Auto-refresh dashboard stats every 1 second for immediate updates
            refreshInterval = setInterval(function() {
                loadRealTimeData();
            }, 1000);
        });

        function initializeCharts() {
            // Weekly Attendance Chart
            const weeklyCtx = document.getElementById('weeklyAttendanceChart').getContext('2d');
            weeklyChart = new Chart(weeklyCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Attendance',
                        data: [],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Attendance: ' + context.raw;
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    }
                }
            });

            // Weekly Revenue Chart
            const revenueCtx = document.getElementById('weeklyRevenueChart').getContext('2d');
            revenueChart = new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: [],
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Revenue: ₱' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        }

        function loadRealTimeData() {
            // Load weekly attendance data
            @if(auth()->user()->role === 'employee')
                fetch(`{{ route("employee.analytics.weekly-attendance") }}?days=${currentAttendancePeriod}`)
            @else
                fetch(`{{ route("analytics.weekly-attendance") }}?days=${currentAttendancePeriod}`)
            @endif
                .then(response => response.json())
                .then(data => {
                    if (weeklyChart) {
                        weeklyChart.data.labels = data.data.map(item => item.day);
                        weeklyChart.data.datasets[0].data = data.data.map(item => item.count);
                        weeklyChart.update('active');
                    }
                    // Update total
                    document.getElementById('attendanceTotal').textContent = `Total: ${data.total}`;
                })
                .catch(error => console.error('Error loading weekly attendance:', error));

            // Load weekly revenue data
            @if(auth()->user()->role === 'employee')
                fetch(`{{ route("employee.analytics.weekly-revenue") }}?days=7`)
            @else
                fetch(`{{ route("analytics.weekly-revenue") }}?days=7`)
            @endif
                .then(response => response.json())
                .then(data => {
                    if (revenueChart) {
                        revenueChart.data.labels = data.data.map(item => item.day);
                        revenueChart.data.datasets[0].data = data.data.map(item => item.revenue);
                        revenueChart.update('active');
                    }
                    // Update total
                    document.getElementById('revenueTotal').textContent = `Total: ₱${data.total.toLocaleString()}`;
                })
                .catch(error => console.error('Error loading weekly revenue:', error));

            // Load dashboard stats
            @if(auth()->user()->role === 'employee')
                fetch('{{ route("employee.analytics.dashboard-stats") }}')
            @else
                fetch('{{ route("analytics.dashboard-stats") }}')
            @endif
                .then(response => response.json())
                .then(data => {
                    // Update current active members count
                    const currentActiveElement = document.getElementById('current-active-members-count');
                    if (currentActiveElement) {
                        currentActiveElement.textContent = data.current_active_members;
                    }
                    
                    // Update today's attendance
                    const todayAttendanceElement = document.getElementById('today-attendance-count');
                    if (todayAttendanceElement) {
                        todayAttendanceElement.textContent = data.today_attendance;
                    }

                    // Update this week's attendance
                    const thisWeekElement = document.querySelector('.grid .bg-white:nth-child(2) .text-2xl');
                    if (thisWeekElement) {
                        thisWeekElement.textContent = data.this_week_attendance;
                    }

                    // Update expiring memberships
                    const expiringElement = document.querySelector('.grid .bg-white:nth-child(3) .text-2xl');
                    const expiringSubtitle = document.querySelector('.grid .bg-white:nth-child(3) .text-xs');
                    if (expiringElement) {
                        expiringElement.textContent = data.expiring_memberships;
                    }
                    if (expiringSubtitle) {
                        expiringSubtitle.textContent = `This week: ${data.expiring_memberships_this_week}`;
                    }

                    // Update total members
                    const totalMembersElement = document.querySelector('.grid .bg-white:nth-child(1) .text-2xl');
                    if (totalMembersElement) {
                        totalMembersElement.textContent = data.total_active_members;
                    }

                    // Update weekly revenue
                    const weeklyRevenueElement = document.querySelector('.bg-gradient-to-br.from-yellow-50 .text-3xl');
                    if (weeklyRevenueElement) {
                        weeklyRevenueElement.textContent = '₱' + data.this_week_revenue.toLocaleString();
                    }
                })
                .catch(error => console.error('Error updating stats:', error));
        }

        // Calendar initialization
        function initializeCalendars() {
            updateAttendanceDateText();
            updateRevenueDateText();
            generateAttendanceCalendar();
            generateRevenueCalendar();
        }

        // Calendar generation functions
        function generateAttendanceCalendar() {
            const container = document.getElementById('attendanceCalendarDays');
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            // Update calendar header
            document.getElementById('attendanceCalendarMonth').textContent = getMonthName(month);
            document.getElementById('attendanceCalendarYear').textContent = year;
            
            // Generate calendar days
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDay = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.
            
            let html = '';
            
            // Previous month's trailing days
            const prevMonth = new Date(year, month - 1, 0);
            const prevMonthDays = prevMonth.getDate();
            for (let i = startingDay - 1; i >= 0; i--) {
                const day = prevMonthDays - i;
                html += `<div class="calendar-day inactive">${day}</div>`;
            }
            
            // Current month's days
            for (let day = 1; day <= daysInMonth; day++) {
                const isToday = day === currentDate.getDate() && month === currentDate.getMonth() && year === currentDate.getFullYear();
                const isSelected = day === 27; // Example selected day
                const classes = ['calendar-day'];
                
                if (isSelected) classes.push('selected');
                if (isToday) classes.push('today');
                
                html += `<div class="${classes.join(' ')}" onclick="selectAttendanceDate(${day})">${day}</div>`;
            }
            
            // Next month's leading days
            const totalCells = 42; // 6 rows × 7 columns
            const usedCells = startingDay + daysInMonth;
            const remainingDays = totalCells - usedCells;
            
            for (let day = 1; day <= remainingDays; day++) {
                html += `<div class="calendar-day inactive">${day}</div>`;
            }
            
            container.innerHTML = html;
        }

        function generateRevenueCalendar() {
            const container = document.getElementById('revenueCalendarDays');
            const year = currentRevenueDate.getFullYear();
            const month = currentRevenueDate.getMonth();
            
            // Update calendar header
            document.getElementById('revenueCalendarMonth').textContent = getMonthName(month);
            document.getElementById('revenueCalendarYear').textContent = year;
            
            // Generate calendar days
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDay = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.
            
            let html = '';
            
            // Previous month's trailing days
            const prevMonth = new Date(year, month - 1, 0);
            const prevMonthDays = prevMonth.getDate();
            for (let i = startingDay - 1; i >= 0; i--) {
                const day = prevMonthDays - i;
                html += `<div class="calendar-day inactive">${day}</div>`;
            }
            
            // Current month's days
            for (let day = 1; day <= daysInMonth; day++) {
                const isToday = day === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear();
                const isSelected = day === 27; // Example selected day
                const classes = ['calendar-day'];
                
                if (isSelected) classes.push('selected');
                if (isToday) classes.push('today');
                
                html += `<div class="${classes.join(' ')}" onclick="selectRevenueDate(${day})">${day}</div>`;
            }
            
            // Next month's leading days
            const totalCells = 42; // 6 rows × 7 columns
            const usedCells = startingDay + daysInMonth;
            const remainingDays = totalCells - usedCells;
            
            for (let day = 1; day <= remainingDays; day++) {
                html += `<div class="calendar-day inactive">${day}</div>`;
            }
            
            container.innerHTML = html;
        }

        // Calendar toggle functions
        function toggleAttendanceCalendar() {
            const calendar = document.getElementById('attendanceCalendar');
            attendanceCalendarVisible = !attendanceCalendarVisible;
            
            if (attendanceCalendarVisible) {
                calendar.classList.remove('hidden');
                document.getElementById('revenueCalendar').classList.add('hidden');
                revenueCalendarVisible = false;
            } else {
                calendar.classList.add('hidden');
            }
        }

        function toggleRevenueCalendar() {
            const calendar = document.getElementById('revenueCalendar');
            revenueCalendarVisible = !revenueCalendarVisible;
            
            if (revenueCalendarVisible) {
                calendar.classList.remove('hidden');
                document.getElementById('attendanceCalendar').classList.add('hidden');
                attendanceCalendarVisible = false;
            } else {
                calendar.classList.add('hidden');
            }
        }

        // Calendar navigation
        function navigateAttendanceMonth(direction) {
            const currentDate = new Date();
            currentDate.setMonth(currentDate.getMonth() + direction);
            generateAttendanceCalendar();
        }

        function navigateRevenueMonth(direction) {
            currentRevenueDate.setMonth(currentRevenueDate.getMonth() + direction);
            generateRevenueCalendar();
            updateRevenueDateText();
            refreshRevenueChart();
        }

        // Date selection functions
        function selectAttendanceDate(day) {
            // Update selected date styling
            const calendarDays = document.querySelectorAll('#attendanceCalendarDays .calendar-day');
            calendarDays.forEach(dayEl => {
                dayEl.classList.remove('selected');
            });
            
            // Highlight selected day
            const selectedDay = Array.from(calendarDays).find(dayEl => 
                dayEl.textContent.trim() === day.toString() && !dayEl.classList.contains('inactive')
            );
            if (selectedDay) {
                selectedDay.classList.add('selected');
            }
            
            // Close calendar and refresh chart
            document.getElementById('attendanceCalendar').classList.add('hidden');
            attendanceCalendarVisible = false;
            refreshAttendanceChart();
        }

        function selectRevenueDate(day) {
            // Update selected date styling
            const calendarDays = document.querySelectorAll('#revenueCalendarDays .calendar-day');
            calendarDays.forEach(dayEl => {
                dayEl.classList.remove('selected');
            });
            
            // Highlight selected day
            const selectedDay = Array.from(calendarDays).find(dayEl => 
                dayEl.textContent.trim() === day.toString() && !dayEl.classList.contains('inactive')
            );
            if (selectedDay) {
                selectedDay.classList.add('selected');
            }
            
            // Close calendar and refresh chart
            document.getElementById('revenueCalendar').classList.add('hidden');
            revenueCalendarVisible = false;
            refreshRevenueChart();
        }

        // Quick period selection
        function selectAttendanceQuickPeriod(days) {
            currentAttendancePeriod = days;
            updateAttendanceDateText();
            document.getElementById('attendanceCalendar').classList.add('hidden');
            attendanceCalendarVisible = false;
            refreshAttendanceChart();
        }

        function selectRevenueQuickPeriod(period) {
            const currentDate = new Date();
            
            switch (period) {
                case 'current':
                    currentRevenueDate = new Date();
                    break;
                case 'previous':
                    currentRevenueDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
                    break;
                case 'year':
                    currentRevenueDate = new Date(currentDate.getFullYear(), 0, 1);
                    break;
            }
            
            updateRevenueDateText();
            generateRevenueCalendar();
            document.getElementById('revenueCalendar').classList.add('hidden');
            revenueCalendarVisible = false;
            refreshRevenueChart();
        }

        // Text update functions
        function updateAttendanceDateText() {
            const periodText = currentAttendancePeriod === 7 ? 'Last 7 days' : 
                              currentAttendancePeriod === 14 ? 'Last 14 days' : 'Last 30 days';
            document.getElementById('attendanceDateText').textContent = periodText;
        }

        function updateRevenueDateText() {
            const monthName = getMonthName(currentRevenueDate.getMonth());
            const year = currentRevenueDate.getFullYear();
            document.getElementById('revenueDateText').textContent = `${monthName} ${year}`;
        }

        // Helper functions
        function getMonthName(monthIndex) {
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                              'July', 'August', 'September', 'October', 'November', 'December'];
            return monthNames[monthIndex];
        }

        // Refresh functions
        function refreshAttendanceChart() {
            @if(auth()->user()->role === 'employee')
                fetch(`{{ route("employee.analytics.weekly-attendance") }}?days=${currentAttendancePeriod}`)
            @else
                fetch(`{{ route("analytics.weekly-attendance") }}?days=${currentAttendancePeriod}`)
            @endif
                .then(response => response.json())
                .then(data => {
                    if (weeklyChart) {
                        weeklyChart.data.labels = data.data.map(item => item.day);
                        weeklyChart.data.datasets[0].data = data.data.map(item => item.count);
                        weeklyChart.update('active');
                    }
                    document.getElementById('attendanceTotal').textContent = `Total: ${data.total}`;
                })
                .catch(error => console.error('Error refreshing attendance chart:', error));
        }

        function refreshRevenueChart() {
            const month = currentRevenueDate.getMonth() + 1;
            const year = currentRevenueDate.getFullYear();
            @if(auth()->user()->role === 'employee')
                fetch(`{{ route("employee.analytics.weekly-revenue") }}?days=7`)
            @else
                fetch(`{{ route("analytics.weekly-revenue") }}?days=7`)
            @endif
                .then(response => response.json())
                .then(data => {
                    if (revenueChart) {
                        revenueChart.data.labels = data.data.map(item => item.day);
                        revenueChart.data.datasets[0].data = data.data.map(item => item.revenue);
                        revenueChart.update('active');
                    }
                    document.getElementById('revenueTotal').textContent = `Total: ₱${data.total.toLocaleString()}`;
                })
                .catch(error => console.error('Error refreshing revenue chart:', error));
        }

        // Clean up interval when page is unloaded
        window.addEventListener('beforeunload', function() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        });

        // Close calendars when clicking outside
        document.addEventListener('click', function(event) {
            const attendanceCalendar = document.getElementById('attendanceCalendar');
            const revenueCalendar = document.getElementById('revenueCalendar');
            
            if (!event.target.closest('#attendanceCalendar') && !event.target.closest('button[onclick="toggleAttendanceCalendar()"]')) {
                attendanceCalendar.classList.add('hidden');
                attendanceCalendarVisible = false;
            }
            
            if (!event.target.closest('#revenueCalendar') && !event.target.closest('button[onclick="toggleRevenueCalendar()"]')) {
                revenueCalendar.classList.add('hidden');
                revenueCalendarVisible = false;
            }
        });

    </script>
</x-layout>