<x-layout>
    <x-nav></x-nav>
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
                        <button onclick="openAddPlanModal()" class="inline-flex items-center justify-center px-4 sm:px-6 py-3 text-white rounded-lg transition-colors shadow-sm min-h-[44px] w-full sm:w-auto" style="background-color: #059669;" onmouseover="this.style.backgroundColor='#047857'" onmouseout="this.style.backgroundColor='#059669'">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="text-sm sm:text-base">Add New Plan Type</span>
                        </button>
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
                                    <span class="text-xs sm:text-sm text-gray-700">Biannually:</span>
                                    <span class="font-semibold text-xs sm:text-sm text-gray-900">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price * 6, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border border-gray-200">
                                    <span class="text-xs sm:text-sm text-gray-700">Annually:</span>
                                    <span class="font-semibold text-xs sm:text-sm text-gray-900">{{ $plan->currency ?? '₱' }}{{ number_format($plan->price * 12, 2) }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                <button onclick="editPlanType('{{ $plan->id }}')" class="flex-1 px-3 sm:px-4 py-2 text-white text-xs sm:text-sm rounded-lg transition-colors min-h-[44px]" style="background-color: #059669;" onmouseover="this.style.backgroundColor='#047857'" onmouseout="this.style.backgroundColor='#059669'">
                                    Edit
                                </button>
                                <button onclick="deletePlanType('{{ $plan->id }}')" class="flex-1 px-3 sm:px-4 py-2 text-white text-xs sm:text-sm rounded-lg transition-colors min-h-[44px]" 
                                        style="background-color: #DC2626; border: 2px solid #E5E7EB; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);" 
                                        onmouseover="this.style.backgroundColor='#B91C1C'" 
                                        onmouseout="this.style.backgroundColor='#DC2626'">
                                    Delete
                                </button>
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
                        <button onclick="openAddDurationModal()" class="inline-flex items-center px-6 py-3 text-white rounded-lg transition-colors shadow-sm" style="background-color: #059669;" onmouseover="this.style.backgroundColor='#047857'" onmouseout="this.style.backgroundColor='#059669'">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add New Duration
                        </button>
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
                                <div class="flex space-x-3">
                                    <button onclick="editDurationType('{{ $key }}')" class="flex-1 px-4 py-2 text-white text-sm rounded-lg transition-colors" style="background-color: #059669;" onmouseover="this.style.backgroundColor='#047857'" onmouseout="this.style.backgroundColor='#059669'">
                                        Edit
                                    </button>
                                    <button onclick="deleteDurationType('{{ $key }}')" class="flex-1 px-4 py-2 text-white text-sm rounded-lg transition-colors" 
                                            style="background-color: #DC2626; border: 2px solid #E5E7EB; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);" 
                                            onmouseover="this.style.backgroundColor='#B91C1C'" 
                                            onmouseout="this.style.backgroundColor='#DC2626'">
                                        Delete
                                    </button>
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
                                <a href="{{ route('membership.manage-member') }}" class="block w-full p-6 border rounded-lg hover:bg-gray-50 transition-colors group" style="border-color: #E5E7EB;">
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
                                <a href="{{ route('membership.payments.index') }}" class="block w-full p-6 border rounded-lg hover:bg-gray-50 transition-colors group" style="border-color: #E5E7EB;">
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

    <!-- Add Plan Type Modal -->
    <div id="addPlanModal" class="fixed inset-0 flex items-center justify-center p-4 hidden z-50">
            <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0 border border-gray-200" id="addPlanModalContent" style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
                <div class="flex items-center justify-between p-6 border-b border-gray-200" style="background-color: #1E40AF;">
                    <h3 class="text-xl font-bold text-white">Add New Plan Type</h3>
                    <button onclick="closeAddPlanModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="addPlanForm" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2" style="color: #374151;">Plan Name</label>
                        <input type="text" name="name" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" style="border-color: #E5E7EB;" placeholder="Enter plan name" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2" style="color: #374151;">Base Price (per month)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 font-semibold" style="color: #6B7280;">₱</span>
                            <input type="number" name="price" step="0.01" min="0" class="w-full pl-8 pr-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" style="border-color: #E5E7EB;" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2" style="color: #374151;">Duration</label>
                        <select name="duration_days" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" style="border-color: #E5E7EB;" required>
                            <option value="">Select duration</option>
                            <option value="30">30 days (Monthly)</option>
                            <option value="90">90 days (Quarterly)</option>
                            <option value="180">180 days (Bi-Annually)</option>
                            <option value="360">360 days (Annually)</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold mb-2" style="color: #374151;">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none" style="border-color: #E5E7EB;" placeholder="Describe the plan features and benefits" required></textarea>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4" style="background-color: #F9FAFB;">
                        <button type="button" onclick="closeAddPlanModal()" class="px-6 py-2 bg-gray-100 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium">
                            Add Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Plan Type Modal -->
    <div id="editPlanModal" class="fixed inset-0 flex items-center justify-center p-4 hidden z-50">
            <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0 border border-gray-200" id="editPlanModalContent" style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
                <div class="flex items-center justify-between p-6 border-b border-gray-200" style="background-color: #1E40AF;">
                    <h3 class="text-xl font-bold text-white">Edit Plan Type</h3>
                    <button onclick="closeEditPlanModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="editPlanForm" class="p-6">
                    <input type="hidden" name="plan_id" id="editPlanId">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2" style="color: #374151;">Plan Name</label>
                        <input type="text" name="name" id="editPlanName" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" style="border-color: #E5E7EB;" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2" style="color: #374151;">Base Price (per month)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 font-semibold" style="color: #6B7280;">₱</span>
                            <input type="number" name="price" id="editPlanPrice" step="0.01" min="0" class="w-full pl-8 pr-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" style="border-color: #E5E7EB;" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2" style="color: #374151;">Duration</label>
                        <select name="duration_days" id="editPlanDuration" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" style="border-color: #E5E7EB;" required>
                            <option value="">Select duration</option>
                            <option value="30">30 days (Monthly)</option>
                            <option value="90">90 days (Quarterly)</option>
                            <option value="180">180 days (Bi-Annually)</option>
                            <option value="360">360 days (Annually)</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2" style="color: #374151;">Description</label>
                        <textarea name="description" id="editPlanDescription" rows="2" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none" style="border-color: #E5E7EB;" required></textarea>
                    </div>
                    <div class="mb-6">
                        <label class="flex items-center p-2 bg-gray-50 rounded border hover:border-blue-300 transition-all duration-200" style="border-color: #E5E7EB;">
                            <input type="checkbox" name="is_active" id="editPlanActive" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-3 text-sm font-semibold" style="color: #374151;">Active Plan</span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4" style="background-color: #F9FAFB;">
                        <button type="button" onclick="closeEditPlanModal()" class="px-6 py-2 bg-gray-100 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium">
                            Update Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Duration Type Modal -->
    <div id="addDurationModal" class="fixed inset-0 flex items-center justify-center p-4 hidden z-50">
            <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0 border border-gray-200" id="addDurationModalContent" style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
                <div class="flex items-center justify-between p-6 border-b border-gray-200" style="background-color: #1E40AF;">
                    <h3 class="text-xl font-bold text-white">Add New Duration Type</h3>
                    <button onclick="closeAddDurationModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="addDurationForm" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Duration Name</label>
                        <input type="text" name="name" class="w-full px-3 py-2 border border-black rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" placeholder="e.g., Monthly, Quarterly" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Multiplier</label>
                        <div class="relative">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">x</span>
                            <input type="number" name="multiplier" step="0.1" min="0.1" class="w-full pr-8 pl-3 py-2 border border-black rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" placeholder="1.0" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">How many times the base monthly price</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (days)</label>
                        <div class="relative">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">days</span>
                            <input type="number" name="days" min="1" class="w-full pr-16 pl-3 py-2 border border-black rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" placeholder="30" required>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4" style="background-color: #F9FAFB;">
                        <button type="button" onclick="closeAddDurationModal()" class="px-6 py-2 bg-gray-100 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium">
                            Add Duration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Duration Type Modal -->
    <div id="editDurationModal" class="fixed inset-0 flex items-center justify-center p-4 hidden z-50">
            <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0 border border-gray-200" id="editDurationModalContent" style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
                <div class="flex items-center justify-between p-6 border-b border-gray-200" style="background-color: #1E40AF;">
                    <h3 class="text-xl font-bold text-white">Edit Duration Type</h3>
                    <button onclick="closeEditDurationModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="editDurationForm" class="p-6">
                    <input type="hidden" name="duration_key" id="editDurationKey">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Duration Name</label>
                        <input type="text" name="name" id="editDurationName" class="w-full px-3 py-2 border border-black rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Multiplier</label>
                        <div class="relative">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">x</span>
                            <input type="number" name="multiplier" id="editDurationMultiplier" step="0.1" min="0.1" class="w-full pr-8 pl-3 py-2 border border-black rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">How many times the base monthly price</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (days)</label>
                        <div class="relative">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">days</span>
                            <input type="number" name="days" id="editDurationDays" min="1" class="w-full pr-16 pl-3 py-2 border border-black rounded focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4" style="background-color: #F9FAFB;">
                        <button type="button" onclick="closeEditDurationModal()" class="px-6 py-2 bg-gray-100 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium">
                            Update Duration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 flex items-center justify-center p-4 hidden z-50">
            <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0 border border-gray-200" 
                 id="deleteConfirmModalContent" style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
                <div class="p-6 text-center">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Are you sure you want to delete this item?</h3>
                        <p id="deleteConfirmMessage" class="text-gray-600">This action cannot be undone.</p>
                    </div>
                    <div class="flex justify-center space-x-3 pt-4" style="background-color: #F9FAFB;">
                        <button onclick="cancelDelete()" class="px-6 py-2 bg-gray-100 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                            Cancel
                        </button>
                        <button onclick="confirmDelete()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 font-medium">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let plans = @json($plans);
        let planTypes = @json($planTypes);
        let durationTypes = @json($durationTypes);

        // Real-time updates every 30 seconds
        setInterval(function() {
            loadPlansData();
        }, 30000);

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

        function loadPlansData() {
            fetch('{{ route("membership-plans.all") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        plans = data.plans;
                        updatePlansDisplay();
                    }
                })
                .catch(error => console.error('Error loading plans:', error));
        }

        function updatePlansDisplay() {
            // Update the plans display with real-time data
            const plansContainer = document.querySelector('.grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3');
            if (plansContainer) {
                // Fetch fresh data and update DOM without page reload
                fetch('{{ route("membership-plans.all") }}')
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
            const plansContainer = document.querySelector('.grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3');
            if (!plansContainer) return;

            const plansHTML = plansData.map(plan => `
                <div class="bg-white border rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-2">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">${plan.name}</h3>
                        ${plan.name === 'VIP' || plan.name === 'Premium' ? 
                            `<span class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-full bg-amber-500 text-white">
                                ₱${parseFloat(plan.price).toFixed(2)}/month
                            </span>` :
                            `<span class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-full bg-green-600 text-white">
                                ₱${parseFloat(plan.price).toFixed(2)}/month
                            </span>`
                        }
                    </div>
                    <p class="mb-4 sm:mb-6 leading-relaxed text-sm sm:text-base text-gray-600">${plan.description}</p>
                    <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border border-gray-200">
                            <span class="text-xs sm:text-sm text-gray-700">Monthly:</span>
                            <span class="font-semibold text-xs sm:text-sm text-gray-900">₱${parseFloat(plan.price).toFixed(2)}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border border-gray-200">
                            <span class="text-xs sm:text-sm text-gray-700">Quarterly:</span>
                            <span class="font-semibold text-xs sm:text-sm text-gray-900">₱${(parseFloat(plan.price) * 3).toFixed(2)}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border border-gray-200">
                            <span class="text-xs sm:text-sm text-gray-700">Biannually:</span>
                            <span class="font-semibold text-xs sm:text-sm text-gray-900">₱${(parseFloat(plan.price) * 6).toFixed(2)}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border border-gray-200">
                            <span class="text-xs sm:text-sm text-gray-700">Annually:</span>
                            <span class="font-semibold text-xs sm:text-sm text-gray-900">₱${(parseFloat(plan.price) * 12).toFixed(2)}</span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button onclick="editPlanType('${plan.id}')" class="flex-1 px-3 sm:px-4 py-2 text-white text-xs sm:text-sm rounded-lg transition-colors min-h-[44px]" style="background-color: #059669;" onmouseover="this.style.backgroundColor='#047857'" onmouseout="this.style.backgroundColor='#059669'">
                            Edit
                        </button>
                        <button onclick="deletePlanType('${plan.id}')" class="flex-1 px-3 sm:px-4 py-2 text-white text-xs sm:text-sm rounded-lg transition-colors min-h-[44px]" 
                                style="background-color: #DC2626; border: 2px solid #E5E7EB; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);" 
                                onmouseover="this.style.backgroundColor='#B91C1C'" 
                                onmouseout="this.style.backgroundColor='#DC2626'">
                            Delete
                        </button>
                    </div>
                </div>
            `).join('');

            plansContainer.innerHTML = plansHTML;
        }

        function updateDurationTypesDisplay() {
            // Update the duration types display with real-time data
            const durationTypesContainer = document.querySelector('.grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-4');
            if (durationTypesContainer) {
                // Fetch fresh data and update DOM without page reload
                fetch('{{ route("membership-plans.duration-types") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            durationTypes = data;
                            renderDurationTypes(durationTypes);
                            showNotification('Duration types updated in real-time', 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating duration types:', error);
                        // Fallback to page reload
                        setTimeout(() => window.location.reload(), 1000);
                    });
            }
        }

        function renderDurationTypes(durationTypesData) {
            const durationTypesContainer = document.querySelector('.grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-4');
            if (!durationTypesContainer) return;

            const durationTypesHTML = Object.entries(durationTypesData).map(([key, duration]) => `
                <div class="bg-white border rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-2">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">${duration.name}</h3>
                        <span class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-full bg-blue-600 text-white">
                            ${duration.days} days
                        </span>
                    </div>
                    <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border border-gray-200">
                            <span class="text-xs sm:text-sm text-gray-700">Multiplier:</span>
                            <span class="font-semibold text-xs sm:text-sm text-gray-900">${duration.multiplier}x</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded border border-gray-200">
                            <span class="text-xs sm:text-sm text-gray-700">Duration:</span>
                            <span class="font-semibold text-xs sm:text-sm text-gray-900">${duration.days} days</span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button onclick="editDurationType('${key}')" class="flex-1 px-3 sm:px-4 py-2 text-white text-xs sm:text-sm rounded-lg transition-colors min-h-[44px]" style="background-color: #059669;" onmouseover="this.style.backgroundColor='#047857'" onmouseout="this.style.backgroundColor='#059669'">
                            Edit
                        </button>
                        <button onclick="deleteDurationType('${key}')" class="flex-1 px-3 sm:px-4 py-2 text-white text-xs sm:text-sm rounded-lg transition-colors min-h-[44px]" 
                                style="background-color: #DC2626; border: 2px solid #E5E7EB; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);" 
                                onmouseover="this.style.backgroundColor='#B91C1C'" 
                                onmouseout="this.style.backgroundColor='#DC2626'">
                            Delete
                        </button>
                    </div>
                </div>
            `).join('');

            durationTypesContainer.innerHTML = durationTypesHTML;
        }

        function openAddPlanModal() {
            const modal = document.getElementById('addPlanModal');
            const content = document.getElementById('addPlanModalContent');
            modal.classList.remove('hidden');
            document.getElementById('addPlanForm').reset();
            
            // Trigger animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeAddPlanModal() {
            const modal = document.getElementById('addPlanModal');
            const content = document.getElementById('addPlanModalContent');
            
            // Trigger close animation
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function editPlanType(planId) {
            const plan = plans.find(p => p.id == planId);
            if (!plan) return;

            // Populate edit modal
            document.getElementById('editPlanId').value = plan.id;
            document.getElementById('editPlanName').value = plan.name;
            document.getElementById('editPlanDescription').value = plan.description;
            document.getElementById('editPlanPrice').value = plan.price;
            document.getElementById('editPlanDuration').value = plan.duration_days;
            document.getElementById('editPlanActive').checked = plan.is_active;
            
            // Features field removed - no longer needed

            const modal = document.getElementById('editPlanModal');
            const content = document.getElementById('editPlanModalContent');
            
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function deletePlanType(planId) {
            const plan = plans.find(p => p.id == planId);
            if (!plan) return;

            const modal = document.getElementById('deleteConfirmModal');
            const content = document.getElementById('deleteConfirmModalContent');

            // Store the plan ID for deletion
            window.pendingDeleteKey = planId;
            window.pendingDeleteType = 'plan';
            
            // Update modal message
            document.getElementById('deleteConfirmMessage').textContent = `Are you sure you want to delete "${plan.name}"? This action cannot be undone.`;
            
            // Show confirmation modal
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function openAddDurationModal() {
            const modal = document.getElementById('addDurationModal');
            const content = document.getElementById('addDurationModalContent');
            modal.classList.remove('hidden');
            document.getElementById('addDurationForm').reset();
            
            // Trigger animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeAddDurationModal() {
            const modal = document.getElementById('addDurationModal');
            const content = document.getElementById('addDurationModalContent');
            
            // Trigger close animation
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function editDurationType(durationKey) {
            const duration = durationTypes[durationKey];
            if (!duration) return;

            const modal = document.getElementById('editDurationModal');
            const content = document.getElementById('editDurationModalContent');

            // Populate edit modal
            document.getElementById('editDurationKey').value = durationKey;
            document.getElementById('editDurationName').value = duration.name;
            document.getElementById('editDurationMultiplier').value = duration.multiplier;
            document.getElementById('editDurationDays').value = duration.days;

            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function deleteDurationType(durationKey) {
            const duration = durationTypes[durationKey];
            if (!duration) return;

            const modal = document.getElementById('deleteConfirmModal');
            const content = document.getElementById('deleteConfirmModalContent');

            // Store the duration key for deletion
            window.pendingDeleteKey = durationKey;
            window.pendingDeleteType = 'duration';
            
            // Update modal message
            document.getElementById('deleteConfirmMessage').textContent = `Are you sure you want to delete "${duration.name}"? This action cannot be undone.`;
            
            // Show confirmation modal
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function updateDurationTypes() {
            fetch('{{ route("membership-plans.update-duration-types") }}', {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    duration_types: durationTypes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Duration types updated successfully', 'success');
                    // Update duration types display in real-time
                    updateDurationTypesDisplay();
                } else {
                    showNotification(data.message || 'Failed to update duration types', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to update duration types', 'error');
            });
        }

        // Form submissions
        document.getElementById('addPlanForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            const data = {
                name: formData.get('name'),
                description: formData.get('description'),
                price: parseFloat(formData.get('price')),
                duration_days: parseInt(formData.get('duration_days')),
                is_active: true
            };

            fetch('{{ route("membership-plans.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Plan created successfully', 'success');
                    closeAddPlanModal();
                    // Update plans display in real-time
                    updatePlansDisplay();
                } else {
                    showNotification(data.message || 'Failed to create plan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to create plan', 'error');
            });
        });

        document.getElementById('editPlanForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const planId = formData.get('plan_id');
            
            const data = {
                name: formData.get('name'),
                description: formData.get('description'),
                price: parseFloat(formData.get('price')),
                duration_days: parseInt(formData.get('duration_days')),
                is_active: formData.get('is_active') === 'on'
            };

            fetch(`{{ route("membership-plans.update", ":id") }}`.replace(':id', planId), {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Plan updated successfully', 'success');
                    closeEditPlanModal();
                    // Update plans display in real-time
                    updatePlansDisplay();
                } else {
                    showNotification(data.message || 'Failed to update plan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to update plan', 'error');
            });
        });

        function closeEditPlanModal() {
            const modal = document.getElementById('editPlanModal');
            const content = document.getElementById('editPlanModalContent');
            
            // Trigger close animation
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function closeEditDurationModal() {
            const modal = document.getElementById('editDurationModal');
            const content = document.getElementById('editDurationModalContent');
            
            // Trigger close animation
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Close modals when clicking outside
        document.getElementById('addPlanModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddPlanModal();
            }
        });

        document.getElementById('editPlanModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditPlanModal();
            }
        });

        document.getElementById('addDurationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddDurationModal();
            }
        });

        document.getElementById('editDurationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditDurationModal();
            }
        });

        document.getElementById('deleteConfirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                cancelDelete();
            }
        });

        // Confirmation modal functions
        function cancelDelete() {
            const modal = document.getElementById('deleteConfirmModal');
            const content = document.getElementById('deleteConfirmModalContent');
            
            // Trigger close animation
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                window.pendingDeleteKey = null;
                window.pendingDeleteType = null;
            }, 300);
        }

        function confirmDelete() {
            if (!window.pendingDeleteKey || !window.pendingDeleteType) return;

            if (window.pendingDeleteType === 'plan') {
                deletePlanConfirmed(window.pendingDeleteKey);
            } else if (window.pendingDeleteType === 'duration') {
                deleteDurationConfirmed(window.pendingDeleteKey);
            }

            cancelDelete();
        }

        function deletePlanConfirmed(planId) {
            fetch(`{{ route("membership-plans.destroy", ":id") }}`.replace(':id', planId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Plan deleted successfully', 'success');
                    // Update plans display in real-time
                    updatePlansDisplay();
                } else {
                    showNotification(data.message || 'Failed to delete plan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to delete plan', 'error');
            });
        }

        function deleteDurationConfirmed(durationKey) {
            // Remove from durationTypes object
            delete durationTypes[durationKey];
            
            // Update configuration
            updateDurationTypes();
        }

        // Duration form submission
        document.getElementById('addDurationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const newKey = formData.get('name').toLowerCase().replace(/\s+/g, '_');
            
            const newDuration = {
                name: formData.get('name'),
                multiplier: parseFloat(formData.get('multiplier')),
                days: parseInt(formData.get('days'))
            };

            // Add to durationTypes object
            durationTypes[newKey] = newDuration;
            
            // Update configuration
            updateDurationTypes();
            
            closeAddDurationModal();
        });

        document.getElementById('editDurationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const durationKey = formData.get('duration_key');
            
            const updatedDuration = {
                name: formData.get('name'),
                multiplier: parseFloat(formData.get('multiplier')),
                days: parseInt(formData.get('days'))
            };

            // Update durationTypes object
            durationTypes[durationKey] = updatedDuration;
            
            // Update configuration
            updateDurationTypes();
            
            closeEditDurationModal();
        });

        // Initialize when DOM is loaded
        // Notification system
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
            
            // Set colors based on type
            switch (type) {
                case 'success':
                    notification.style.backgroundColor = '#10B981';
                    notification.style.color = 'white';
                    break;
                case 'error':
                    notification.style.backgroundColor = '#EF4444';
                    notification.style.color = 'white';
                    break;
                case 'warning':
                    notification.style.backgroundColor = '#F59E0B';
                    notification.style.color = 'white';
                    break;
                default:
                    notification.style.backgroundColor = '#3B82F6';
                    notification.style.color = 'white';
            }

            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            // Add to page
            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(full)';
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeSSE();
        });
    </script>
</x-layout>
