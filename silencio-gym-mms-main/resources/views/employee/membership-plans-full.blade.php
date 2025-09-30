<x-layout>
    <x-nav-employee></x-nav-employee>
    <div class="flex-1 bg-white">
        <x-topbar>Membership Plans Configuration</x-topbar>

        <div class="bg-white min-h-screen p-6">
            <!-- Plan Types Section -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-bold" style="color: #000000;">Plan Types</h2>
                        <div class="flex items-center space-x-2">
                            <div id="realtime-indicator" class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                            <span class="text-sm text-gray-600">Real-time</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach($plans as $plan)
                        <div class="bg-white border rounded-lg p-6 hover:shadow-lg transition-shadow" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold" style="color: #000000;">{{ $plan->name }}</h3>
                                @if($plan->name === 'VIP' || $plan->name === 'Premium')
                                <span class="px-4 py-2 text-sm font-medium rounded-full" style="background-color: #F59E0B; color: #000000;">
                                    {{ $plan->currency ?? '₱' }}{{ number_format($plan->price, 2) }}/month
                                </span>
                                @else
                                <span class="px-4 py-2 text-sm font-medium rounded-full" style="background-color: #059669; color: #000000;">
                                    {{ $plan->currency ?? '₱' }}{{ number_format($plan->price, 2) }}/month
                                </span>
                                @endif
                            </div>
                            <p class="mb-6 leading-relaxed" style="color: #6B7280;">{{ $plan->description }}</p>
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                    <span class="text-sm" style="color: #374151;">Monthly:</span>
                                    <span class="font-semibold" style="color: #000000;">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                    <span class="text-sm" style="color: #374151;">Quarterly:</span>
                                    <span class="font-semibold" style="color: #000000;">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price * 3, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                    <span class="text-sm" style="color: #374151;">Biannually:</span>
                                    <span class="font-semibold" style="color: #000000;">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price * 6, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                    <span class="text-sm" style="color: #374151;">Annually:</span>
                                    <span class="font-semibold" style="color: #000000;">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price * 12, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Duration Types Section -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-bold" style="color: #000000;">Duration Types</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        @foreach(config('membership.duration_types') as $key => $duration)
                        <div class="bg-white border rounded-lg p-6 hover:shadow-lg transition-shadow" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <div class="text-center">
                                <h3 class="text-xl font-semibold mb-4" style="color: #000000;">{{ $duration['name'] }}</h3>
                                <div class="text-4xl font-bold mb-3" style="color: #1E40AF;">{{ $duration['multiplier'] }}x</div>
                                <p class="text-sm mb-6" style="color: #6B7280;">{{ $duration['days'] }} days</p>
                                <div class="space-y-3 mb-6">
                                    @foreach(config('membership.plan_types') as $planKey => $plan)
                                    <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                        <span class="text-sm" style="color: #374151;">{{ $plan['name'] }}:</span>
                                        <span class="font-semibold" style="color: #000000;">₱{{ number_format($plan['base_price'] * $duration['multiplier'], 2) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Pricing Calculator Preview -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h2 class="text-3xl font-bold mb-8" style="color: #000000;">Pricing Calculator Preview</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <div>
                            <h3 class="text-xl font-semibold mb-6" style="color: #000000;">Plan Type + Duration = Total Price</h3>
                            <div class="space-y-4">
                                @foreach(config('membership.plan_types') as $planKey => $plan)
                                    @foreach(config('membership.duration_types') as $durationKey => $duration)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors" style="border-color: #E5E7EB;">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-medium" style="color: #000000;">{{ $plan['name'] }}</span>
                                            <span style="color: #6B7280;">+</span>
                                            <span class="font-medium" style="color: #000000;">{{ $duration['name'] }}</span>
                                        </div>
                                        <span class="font-bold text-lg" style="color: #000000;">₱{{ number_format($plan['base_price'] * $duration['multiplier'], 2) }}</span>
                                    </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold mb-6" style="color: #000000;">Quick Actions</h3>
                            <div class="space-y-4">
                                <a href="{{ route('employee.membership.manage-member') }}" class="block w-full p-6 border rounded-lg hover:bg-gray-50 transition-colors group" style="border-color: #E5E7EB;">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4 group-hover:bg-gray-100 transition-colors" style="background-color: #1E40AF;">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold mb-1" style="color: #1E40AF;">Manage Member Plans</h4>
                                            <p style="color: #6B7280;">Process payments and activate memberships</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="{{ route('employee.membership.payments') }}" class="block w-full p-6 border rounded-lg hover:bg-gray-50 transition-colors group" style="border-color: #E5E7EB;">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4 group-hover:bg-gray-100 transition-colors" style="background-color: #059669;">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold mb-1" style="color: #059669;">View All Payments</h4>
                                            <p style="color: #6B7280;">See payment history and manage records</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let plans = @json($plans);
        let planTypes = @json($planTypes);
        let durationTypes = @json($durationTypes);
        let lastPlansHash = '';
        let lastPlanTypesHash = '';
        let lastDurationTypesHash = '';

        // Real-time updates every 30 seconds (fallback)
        setInterval(function() {
            loadPlansData();
        }, 30000);

        function loadPlansData() {
            // Load plans data
            fetch('{{ route("employee.membership-plans.all") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const newHash = JSON.stringify(data.plans);
                        if (newHash !== lastPlansHash) {
                            plans = data.plans;
                            updatePlansDisplay();
                            lastPlansHash = newHash;
                        }
                    }
                })
                .catch(error => console.error('Error loading plans:', error));

            // Load plan types
            fetch('{{ route("employee.membership-plans.plan-types") }}')
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        const newHash = JSON.stringify(data);
                        if (newHash !== lastPlanTypesHash) {
                            planTypes = data;
                            updatePlanTypesDisplay();
                            lastPlanTypesHash = newHash;
                        }
                    }
                })
                .catch(error => console.error('Error loading plan types:', error));

            // Load duration types
            fetch('{{ route("employee.membership-plans.duration-types") }}')
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        const newHash = JSON.stringify(data);
                        if (newHash !== lastDurationTypesHash) {
                            durationTypes = data;
                            updateDurationTypesDisplay();
                            lastDurationTypesHash = newHash;
                        }
                    }
                })
                .catch(error => console.error('Error loading duration types:', error));
        }

        function updatePlansDisplay() {
            // Update the plans display with real-time data
            const plansContainer = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-3');
            if (plansContainer) {
                // Fetch fresh data and update DOM without page reload
                fetch('{{ route("employee.membership-plans.all") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.plans) {
                            plans = data.plans;
                            renderPlans(plans);
                            showNotification('Plans updated in real-time', 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating plans:', error);
                        // Fallback to page reload
                        setTimeout(() => window.location.reload(), 1000);
                    });
            }
        }

        function renderPlans(plansData) {
            const plansContainer = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-3');
            if (!plansContainer) return;

            const plansHTML = plansData.map(plan => `
                <div class="bg-white border rounded-lg p-6 hover:shadow-lg transition-shadow" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold" style="color: #000000;">${plan.name}</h3>
                        ${plan.name === 'VIP' || plan.name === 'Premium' ? 
                            `<span class="px-4 py-2 text-sm font-medium rounded-full" style="background-color: #F59E0B; color: #000000;">
                                ₱${parseFloat(plan.price).toFixed(2)}/month
                            </span>` :
                            `<span class="px-4 py-2 text-sm font-medium rounded-full" style="background-color: #059669; color: #000000;">
                                ₱${parseFloat(plan.price).toFixed(2)}/month
                            </span>`
                        }
                    </div>
                    <p class="mb-6 leading-relaxed" style="color: #6B7280;">${plan.description}</p>
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                            <span class="text-sm" style="color: #374151;">Monthly:</span>
                            <span class="font-semibold" style="color: #000000;">₱${parseFloat(plan.price).toFixed(2)}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                            <span class="text-sm" style="color: #374151;">Quarterly:</span>
                            <span class="font-semibold" style="color: #000000;">₱${(parseFloat(plan.price) * 3).toFixed(2)}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                            <span class="text-sm" style="color: #374151;">Biannually:</span>
                            <span class="font-semibold" style="color: #000000;">₱${(parseFloat(plan.price) * 6).toFixed(2)}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                            <span class="text-sm" style="color: #374151;">Annually:</span>
                            <span class="font-semibold" style="color: #000000;">₱${(parseFloat(plan.price) * 12).toFixed(2)}</span>
                        </div>
                    </div>
                    <p class="text-xs" style="color: #6B7280;">Read-only view</p>
                </div>
            `).join('');

            plansContainer.innerHTML = plansHTML;
        }

        function updatePlanTypesDisplay() {
            // Update plan types display with real-time data
            fetch('{{ route("employee.membership-plans.plan-types") }}')
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        planTypes = data;
                        showNotification('Plan types updated in real-time', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error updating plan types:', error);
                    // Fallback to page reload
                    setTimeout(() => window.location.reload(), 1000);
                });
        }

        function updateDurationTypesDisplay() {
            // Update duration types display with real-time data
            fetch('{{ route("employee.membership-plans.duration-types") }}')
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        durationTypes = data;
                        showNotification('Duration types updated in real-time', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error updating duration types:', error);
                    // Fallback to page reload
                    setTimeout(() => window.location.reload(), 1000);
                });
        }

        // Update real-time indicator status
        function updateRealtimeIndicator(isActive) {
            const indicator = document.getElementById('realtime-indicator');
            if (indicator) {
                if (isActive) {
                    indicator.className = 'w-3 h-3 rounded-full bg-green-500 animate-pulse';
                } else {
                    indicator.className = 'w-3 h-3 rounded-full bg-red-500';
                }
            }
        }

        // Initialize Server-Sent Events for real-time updates
        function initializeSSE() {
            if (typeof(EventSource) !== "undefined") {
                const eventSource = new EventSource('{{ route("member.membership-plans.stream") }}');
                
                eventSource.onmessage = function(event) {
                    try {
                        const data = JSON.parse(event.data);
                        
                        // Update real-time indicator
                        updateRealtimeIndicator(true);
                        
                        if (data.plans_changed) {
                            plans = data.plans;
                            updatePlansDisplay();
                        }
                        
                        if (data.pricing_changed) {
                            // Pricing changes affect plan types and duration types
                            updatePlanTypesDisplay();
                        }
                        
                        console.log('Real-time update received:', data.timestamp);
                    } catch (error) {
                        console.error('Error parsing SSE data:', error);
                        updateRealtimeIndicator(false);
                    }
                };
                
                eventSource.onerror = function(event) {
                    console.error('SSE connection error:', event);
                    updateRealtimeIndicator(false);
                };
            } else {
                console.log('Server-Sent Events not supported');
                updateRealtimeIndicator(false);
            }
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPlansData();
            initializeSSE();
        });
    </script>
</x-layout>