<x-layout>
    <x-nav-employee></x-nav-employee>
    <div class="flex-1 bg-white">
        <x-topbar>Membership Plans Configuration</x-topbar>

        <div class="bg-white min-h-screen p-4 sm:p-6">
            <!-- Plan Types Section -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 lg:p-8" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8 gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Plan Types</h2>
                            <div class="flex items-center space-x-2">
                                <div id="realtime-indicator" class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                                <span class="text-xs sm:text-sm text-gray-600">Real-time</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                        @foreach($plans as $plan)
                        <div class="bg-white border rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-2">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                                @if($plan->name === 'VIP' || $plan->name === 'Premium')
                                <span class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-full bg-amber-500 text-white">
                                    {{ $plan->currency ?? '₱' }}{{ number_format($plan->price, 2) }}/month
                                </span>
                                @else
                                <span class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-full bg-green-600 text-white">
                                    {{ $plan->currency ?? '₱' }}{{ number_format($plan->price, 2) }}/month
                                </span>
                                @endif
                            </div>
                            <p class="mb-4 sm:mb-6 leading-relaxed text-sm sm:text-base text-gray-600">{{ $plan->description }}</p>
                            <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border border-gray-200">
                                    <span class="text-xs sm:text-sm text-gray-700">Monthly:</span>
                                    <span class="font-semibold text-xs sm:text-sm text-gray-900">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border border-gray-200">
                                    <span class="text-xs sm:text-sm text-gray-700">Quarterly:</span>
                                    <span class="font-semibold text-xs sm:text-sm text-gray-900">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price * 3, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border border-gray-200">
                                    <span class="text-xs sm:text-sm text-gray-700">Yearly:</span>
                                    <span class="font-semibold text-xs sm:text-sm text-gray-900">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price * 12, 2) }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs sm:text-sm text-gray-500">Status:</span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Duration Types Section -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 lg:p-8" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8 gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Duration Types</h2>
                            <div class="flex items-center space-x-2">
                                <div id="realtime-indicator-duration" class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                                <span class="text-xs sm:text-sm text-gray-600">Real-time</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        @foreach(config('membership.duration_types') as $duration)
                        <div class="bg-white border rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <div class="text-center">
                                <h3 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-gray-900">{{ $duration['name'] }}</h3>
                                <div class="text-3xl sm:text-4xl font-bold mb-2 sm:mb-3 text-blue-600">{{ $duration['multiplier'] }}x</div>
                                <p class="text-xs sm:text-sm mb-4 sm:mb-6 text-gray-600">{{ $duration['days'] }} days</p>
                                <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                                    @foreach(config('membership.plan_types') as $planKey => $plan)
                                    <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border border-gray-200">
                                        <span class="text-xs sm:text-sm text-gray-700">{{ $plan['name'] }}:</span>
                                        <span class="font-semibold text-xs sm:text-sm text-gray-900">₱{{ number_format($plan['base_price'] * $duration['multiplier'], 2) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 lg:p-8" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 sm:mb-8">Quick Actions</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <a href="{{ route('employee.membership.manage-member') }}" class="block w-full p-4 sm:p-6 border rounded-lg hover:bg-gray-50 transition-colors group" style="border-color: #E5E7EB;">
                                    <div class="flex items-center">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center mr-3 sm:mr-4 group-hover:bg-gray-100 transition-colors" style="background-color: #1E40AF;">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </div>
                                        <div>
                                    <h4 class="text-base sm:text-lg font-semibold mb-1 text-gray-900">Manage Member Plans</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">Process payments and activate memberships</p>
                                        </div>
                                    </div>
                                </a>
                        
                        <a href="{{ route('employee.membership.payments') }}" class="block w-full p-4 sm:p-6 border rounded-lg hover:bg-gray-50 transition-colors group" style="border-color: #E5E7EB;">
                                    <div class="flex items-center">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center mr-3 sm:mr-4 group-hover:bg-gray-100 transition-colors" style="background-color: #059669;">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                    <h4 class="text-base sm:text-lg font-semibold mb-1 text-gray-900">View All Payments</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">See payment history and manage records</p>
                                        </div>
                                    </div>
                                </a>
                        
                        <a href="{{ route('employee.members') }}" class="block w-full p-4 sm:p-6 border rounded-lg hover:bg-gray-50 transition-colors group" style="border-color: #E5E7EB;">
                            <div class="flex items-center">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center mr-3 sm:mr-4 group-hover:bg-gray-100 transition-colors" style="background-color: #7C3AED;">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-base sm:text-lg font-semibold mb-1 text-gray-900">Manage Members</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">View and manage member accounts</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let plans = @json($plans);
        let planTypes = @json(config('membership.plan_types'));
        let durationTypes = @json(config('membership.duration_types'));
        let lastPlansHash = '';
        let lastPlanTypesHash = '';
        let lastDurationTypesHash = '';

        // Real-time updates every 30 seconds (fallback)
        setInterval(function() {
            loadPlansData();
        }, 30000);

        function loadPlansData() {
            // Load plans
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
            // Update plans display if needed
            console.log('Plans updated:', plans);
        }

        function updatePlanTypesDisplay() {
            // Update plan types display if needed
            console.log('Plan types updated:', planTypes);
        }

        function updateDurationTypesDisplay() {
            // Update duration types display if needed
            console.log('Duration types updated:', durationTypes);
        }

        // Initial load
        document.addEventListener('DOMContentLoaded', function() {
            loadPlansData();
        });
    </script>
</x-layout>