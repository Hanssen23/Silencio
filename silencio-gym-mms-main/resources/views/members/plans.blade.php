<x-layout>
    <x-nav-member></x-nav-member>
    <div class="flex-1 bg-white">
        <x-topbar>Membership Plans</x-topbar>

        <div class="bg-white min-h-screen p-6">
            <!-- Plan Types Section -->
            <div class="mb-8">
                <div class="bg-white rounded-lg border p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-bold" style="color: #1E40AF;">Plan Types</h2>
                        <div class="flex items-center space-x-2">
                            <div id="realtime-indicator" class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                            <span class="text-sm text-gray-600">Real-time</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @foreach($plans as $plan)
                        <div class="bg-white border rounded-lg p-6" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold" style="color: #000000;">{{ $plan->name }}</h3>
                                <span class="px-4 py-2 text-sm font-semibold rounded-full text-white" 
                                      style="background-color: {{ $plan->name === 'VIP' || $plan->name === 'Premium' ? '#F59E0B' : '#059669' }};">
                                    {{ $plan->currency ?? '₱' }}{{ number_format($plan->price, 2) }}/month
                                </span>
                            </div>
                            <p class="mb-6 leading-relaxed" style="color: #6B7280;">{{ $plan->description }}</p>
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                    <span class="text-sm" style="color: #374151;">Monthly:</span>
                                    <span class="font-semibold" style="color: #000000;">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                    <span class="text-sm" style="color: #374151;">Quarterly:</span>
                                    <span class="font-semibold" style="color: #000000;">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price * 3, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                    <span class="text-sm" style="color: #374151;">Biannually:</span>
                                    <span class="font-semibold" style="color: #000000;">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price * 6, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                    <span class="text-sm" style="color: #374151;">Annually:</span>
                                    <span class="font-semibold" style="color: #000000;">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price * 12, 2) }}</span>
                                </div>
                            </div>
                            <p class="text-xs" style="color: #6B7280;">Read-only view</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Duration Types Section -->
            <div class="mb-8">
                <div class="bg-white rounded-lg border p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-bold" style="color: #1E40AF;">Duration Types</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        @foreach(config('membership.duration_types') as $key => $duration)
                        <div class="bg-white border rounded-lg p-6" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <div class="text-center">
                                <h3 class="text-xl font-bold mb-4" style="color: #000000;">{{ $duration['name'] }}</h3>
                                <div class="text-4xl font-bold mb-3" style="color: #1E40AF;">{{ $duration['multiplier'] }}x</div>
                                <p class="text-sm mb-6" style="color: #6B7280;">{{ $duration['days'] }} days</p>
                                <div class="space-y-3 mb-6">
                                    @foreach(config('membership.plan_types') as $planKey => $plan)
                                    <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                        <span class="text-sm" style="color: #374151;">{{ $plan['name'] }}:</span>
                                        <span class="font-semibold" style="color: #000000;">₱{{ number_format($plan['base_price'] * $duration['multiplier'], 2) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                <p class="text-xs" style="color: #6B7280;">Read-only view</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Pricing Calculator Preview -->
            <div class="mb-8">
                <div class="bg-white rounded-lg border p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <h2 class="text-3xl font-bold mb-8" style="color: #1E40AF;">Pricing Calculator</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <div>
                            <h3 class="text-xl font-bold mb-6" style="color: #000000;">Plan Type + Duration = Total Price</h3>
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
                            <h3 class="text-xl font-bold mb-6" style="color: #000000;">Current Membership</h3>
                            <div class="bg-gray-50 border rounded-lg p-6" style="border-color: #E5E7EB;">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" style="background-color: #1E40AF;">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold mb-1" style="color: #1E40AF;">Your Membership</h4>
                                        <p style="color: #6B7280;">View your current plan details</p>
                                    </div>
                                </div>
                                <a href="/member" class="block w-full px-4 py-3 text-white text-center rounded-lg transition-colors" 
                                   style="background-color: #2563EB;" 
                                   onmouseover="this.style.backgroundColor='#1D4ED8'" 
                                   onmouseout="this.style.backgroundColor='#2563EB'">
                                    Go to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let plans = [];
        let pricing = [];
        let lastPlansHash = '';
        let lastPricingHash = '';

        // Real-time updates every 15 seconds for better responsiveness (fallback only)
        // setInterval(function() {
        //     loadPlansData();
        // }, 15000);

        function loadPlansData() {
            // Load active plans
            fetch('{{ route("member.membership-plans") }}')
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        const newHash = JSON.stringify(data);
                        if (newHash !== lastPlansHash) {
                            plans = data;
                            updatePlansDisplay();
                            lastPlansHash = newHash;
                        }
                    }
                })
                .catch(error => console.error('Error loading plans:', error));

            // Load pricing information
            fetch('{{ route("member.membership-pricing") }}')
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        const newHash = JSON.stringify(data);
                        if (newHash !== lastPricingHash) {
                            pricing = data;
                            updatePricingDisplay();
                            lastPricingHash = newHash;
                        }
                    }
                })
                .catch(error => console.error('Error loading pricing:', error));
        }

        function updatePlansDisplay() {
            // Update the plans display with real-time data
            const plansContainer = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-3');
            if (plansContainer) {
                // Fetch fresh data and update DOM without page reload
                fetch('{{ route("member.membership-pricing") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.plans) {
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

            const newPlansHTML = plansData.map(plan => `
                    <div class="bg-white border rounded-lg p-6" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold" style="color: #000000;">${plan.name}</h3>
                            <span class="px-4 py-2 text-sm font-semibold rounded-full text-white" 
                                  style="background-color: ${plan.name === 'VIP' || plan.name === 'Premium' ? '#F59E0B' : '#059669'};">
                                ₱${parseFloat(plan.price).toFixed(2)}/month
                            </span>
                        </div>
                        <p class="mb-6 leading-relaxed" style="color: #6B7280;">${plan.description}</p>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                <span class="text-sm" style="color: #374151;">Monthly:</span>
                                <span class="font-semibold" style="color: #000000;">₱${parseFloat(plan.price).toFixed(2)}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                <span class="text-sm" style="color: #374151;">Quarterly:</span>
                                <span class="font-semibold" style="color: #000000;">₱${(parseFloat(plan.price) * 3).toFixed(2)}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                <span class="text-sm" style="color: #374151;">Biannually:</span>
                                <span class="font-semibold" style="color: #000000;">₱${(parseFloat(plan.price) * 6).toFixed(2)}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded border" style="border-color: #E5E7EB;">
                                <span class="text-sm" style="color: #374151;">Annually:</span>
                                <span class="font-semibold" style="color: #000000;">₱${(parseFloat(plan.price) * 12).toFixed(2)}</span>
                            </div>
                        </div>
                        <p class="text-xs" style="color: #6B7280;">Read-only view</p>
                    </div>
                `).join('');

            // Update the container with new content
            plansContainer.innerHTML = newPlansHTML;
            
            // Add visual feedback for update
            plansContainer.style.opacity = '0.7';
            setTimeout(() => {
                plansContainer.style.opacity = '1';
            }, 200);
            }
        }

        function updatePricingDisplay() {
            // Update pricing display with real-time data
            if (pricing.length > 0) {
                // Update the pricing calculator section
                const pricingContainer = document.querySelector('.space-y-4');
                if (pricingContainer) {
                    const newPricingHTML = pricing.map(planPricing => {
                        return Object.values(planPricing.durations || {}).map(duration => `
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors" style="border-color: #E5E7EB;">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium" style="color: #000000;">${planPricing.plan_name}</span>
                                    <span style="color: #6B7280;">+</span>
                                    <span class="font-medium" style="color: #000000;">${duration.name}</span>
                                </div>
                                <span class="font-bold text-lg" style="color: #000000;">₱${parseFloat(duration.price).toFixed(2)}</span>
                            </div>
                        `).join('');
                    }).join('');

                    // Update pricing section if it exists
                    const pricingSection = document.querySelector('.space-y-4');
                    if (pricingSection && newPricingHTML) {
                        pricingSection.innerHTML = newPricingHTML;
                        
                        // Add visual feedback
                        pricingSection.style.opacity = '0.7';
                        setTimeout(() => {
                            pricingSection.style.opacity = '1';
                        }, 200);
                    }
                }
                
                console.log('Pricing updated:', pricing);
            }
        }

        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300';
            
            if (type === 'success') {
                notification.style.backgroundColor = '#059669';
                notification.style.color = '#FFFFFF';
            } else if (type === 'error') {
                notification.style.backgroundColor = '#DC2626';
                notification.style.color = '#FFFFFF';
            } else {
                notification.style.backgroundColor = '#2563EB';
                notification.style.color = '#FFFFFF';
            }
            
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="text-xl">${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPlansData();
            initializeSSE();
        });

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
                            pricing = data.pricing;
                            updatePricingDisplay();
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
                    // Fallback to polling if SSE fails
                    setTimeout(() => {
                        loadPlansData();
                    }, 5000);
                };
                
                // Clean up on page unload
                window.addEventListener('beforeunload', function() {
                    eventSource.close();
                });
            } else {
                console.log('SSE not supported, falling back to polling');
                // Fallback to regular polling if SSE is not supported
                setInterval(function() {
                    loadPlansData();
                }, 15000);
            }
        }
    </script>
</x-layout>


