<x-layout>
    <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Terms and Conditions</h1>
                <p class="text-gray-600">Ripped Body Anytime Gym Membership Agreement</p>
                <p class="text-sm text-gray-500 mt-2">Last updated: {{ date('F d, Y') }}</p>
            </div>
        </div>

        <!-- Terms Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-8">
            
            <!-- 1. Membership Rules -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-full mr-3">1</span>
                    Membership Rules and Gym Conduct
                </h2>
                <div class="prose prose-gray max-w-none">
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span>Members must be at least 16 years old to use the gym facilities independently. Members under 16 must be accompanied by a parent or guardian.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span>Proper gym attire is required at all times. Closed-toe athletic shoes are mandatory for safety.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span>Members must wipe down equipment after use and return weights to their designated areas.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span>No food or drinks (except water in closed containers) are allowed in workout areas.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span>Respectful behavior towards staff and other members is required. Harassment or inappropriate conduct will result in immediate termination of membership.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-blue-600 mr-2">•</span>
                            <span>Members must follow all safety guidelines and instructions from gym staff.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- 2. Payment Terms -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded-full mr-3">2</span>
                    Payment Terms and Membership Fees
                </h2>
                <div class="prose prose-gray max-w-none">
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">•</span>
                            <span>Membership fees are due in advance and are non-refundable once payment is processed.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">•</span>
                            <span>Membership periods are fixed and cannot be paused or extended without prior written approval.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">•</span>
                            <span>Late payments may result in suspension of gym access until payment is received.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">•</span>
                            <span>Membership auto-renewal is available for monthly plans. Members can cancel auto-renewal at any time.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">•</span>
                            <span>PWD and Senior Citizen discounts (20% each, up to 40% combined) are available with valid identification.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-600 mr-2">•</span>
                            <span>All payments are processed securely and receipts are provided for all transactions.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- 3. Privacy Policy -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="bg-purple-100 text-purple-800 text-sm font-medium px-2.5 py-0.5 rounded-full mr-3">3</span>
                    Privacy Policy and Data Protection
                </h2>
                <div class="prose prose-gray max-w-none">
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-purple-600 mr-2">•</span>
                            <span>We collect and store personal information including name, email, phone number, and payment information for membership management purposes.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-purple-600 mr-2">•</span>
                            <span>Personal information is used solely for gym operations, membership management, and communication regarding your account.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-purple-600 mr-2">•</span>
                            <span>We do not sell, trade, or share personal information with third parties without explicit consent.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-purple-600 mr-2">•</span>
                            <span>RFID card data and gym access logs are stored securely and used for security and attendance tracking.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-purple-600 mr-2">•</span>
                            <span>Members can request access to their personal data or request data deletion by contacting gym management.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-purple-600 mr-2">•</span>
                            <span>All data is protected using industry-standard security measures and encryption.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- 4. Liability Waiver -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded-full mr-3">4</span>
                    Liability Waiver and Assumption of Risk
                </h2>
                <div class="prose prose-gray max-w-none">
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-red-600 mr-2">•</span>
                            <span>Members acknowledge that participation in physical exercise involves inherent risks of injury.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-600 mr-2">•</span>
                            <span>Members voluntarily assume all risks associated with gym use and exercise activities.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-600 mr-2">•</span>
                            <span>Ripped Body Anytime Gym, its staff, and owners are not liable for any injuries, accidents, or damages that may occur during gym use.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-600 mr-2">•</span>
                            <span>Members are responsible for their own safety and should consult with healthcare providers before beginning any exercise program.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-600 mr-2">•</span>
                            <span>Members must report any equipment malfunctions or safety hazards to staff immediately.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-red-600 mr-2">•</span>
                            <span>Personal belongings are brought at the member's own risk. The gym is not responsible for lost or stolen items.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- 5. Membership Termination -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-2.5 py-0.5 rounded-full mr-3">5</span>
                    Membership Termination and Cancellation
                </h2>
                <div class="prose prose-gray max-w-none">
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-yellow-600 mr-2">•</span>
                            <span>Memberships can be terminated by either party with 30 days written notice.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-yellow-600 mr-2">•</span>
                            <span>Violation of gym rules or inappropriate conduct may result in immediate membership termination without refund.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-yellow-600 mr-2">•</span>
                            <span>Upon termination, members must return all gym property including RFID cards and access keys.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-yellow-600 mr-2">•</span>
                            <span>Refunds for unused membership periods are not provided unless required by law.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- 6. General Terms -->
            <section>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="bg-gray-100 text-gray-800 text-sm font-medium px-2.5 py-0.5 rounded-full mr-3">6</span>
                    General Terms and Conditions
                </h2>
                <div class="prose prose-gray max-w-none">
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-gray-600 mr-2">•</span>
                            <span>These terms and conditions are subject to change with 30 days notice to members.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-gray-600 mr-2">•</span>
                            <span>Gym hours and services may be modified due to maintenance, holidays, or unforeseen circumstances.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-gray-600 mr-2">•</span>
                            <span>Members are responsible for keeping their contact information up to date.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-gray-600 mr-2">•</span>
                            <span>Any disputes will be resolved through binding arbitration in accordance with local laws.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- Contact Information -->
            <section class="bg-gray-50 rounded-lg p-6 mt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <p><strong>Gym Address:</strong></p>
                        <p>Ripped Body Anytime Gym</p>
                        <p>123 Fitness Street</p>
                        <p>City, State 12345</p>
                    </div>
                    <div>
                        <p><strong>Contact Details:</strong></p>
                        <p>Phone: (555) 123-4567</p>
                        <p>Email: info@rippedbodyanytime.com</p>
                        <p>Website: www.rippedbodyanytime.com</p>
                    </div>
                </div>
            </section>

            <!-- Agreement Statement -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
                <p class="text-sm text-blue-800 text-center">
                    <strong>By registering for membership, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions.</strong>
                </p>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-8">
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Registration
            </a>
        </div>
    </div>
</div>
</x-layout>
