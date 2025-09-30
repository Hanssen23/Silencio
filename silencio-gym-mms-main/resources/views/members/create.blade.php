<x-layout>
    <x-nav></x-nav>
    <div class="flex-1 bg-gray-100">
        <x-topbar>Members</x-topbar>

        <!-- Main Content -->
        <div class="p-4 sm:p-6">
            <!-- Header with Back Button -->
            <div class="mb-4 sm:mb-6 sticky top-16 sm:top-20 z-10 -mx-4 sm:-mx-6 px-4 sm:px-6 py-3 bg-white/90 backdrop-blur border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                    <a href="{{ route('members.index') }}" class="flex items-center gap-2 text-black hover:text-red-600 transition-colors duration-200 min-h-[44px]">
                        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="font-medium text-sm sm:text-base">Back to Members</span>
                    </a>
                    <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-black">Create New Member</h2>
                </div>
            </div>

            <!-- Create Member Form -->
            <div class="bg-white rounded-lg shadow-sm border border-black p-4 sm:p-6">
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-2 border-red-300 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <form method="POST" action="{{ route('members.store') }}" class="space-y-4 sm:space-y-6">
                    @csrf
                    
                    


                    <!-- First Name Field -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-black mb-2">First Name</label>
                        <input type="text" id="first_name" name="first_name" required 
                               class="w-full px-3 sm:px-4 py-3 border {{ $errors->has('first_name') ? 'border-red-500' : 'border-black' }} bg-white text-black rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors duration-200 placeholder-gray-500 min-h-[44px] text-sm sm:text-base"
                               placeholder="Enter first name (letters, spaces, and hyphens only)"
                               value="{{ old('first_name') }}"
                               pattern="^[a-zA-Z\s\-]+$"
                               title="First name can only contain letters, spaces, and hyphens">
                        @error('first_name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        <div id="first_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Last Name Field -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-black mb-2">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required 
                               class="w-full px-3 sm:px-4 py-3 border {{ $errors->has('last_name') ? 'border-red-500' : 'border-black' }} bg-white text-black rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors duration-200 placeholder-gray-500 min-h-[44px] text-sm sm:text-base"
                               placeholder="Enter last name (letters, spaces, and hyphens only)"
                               value="{{ old('last_name') }}"
                               pattern="^[a-zA-Z\s\-]+$"
                               title="Last name can only contain letters, spaces, and hyphens">
                        @error('last_name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        <div id="last_name_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Mobile Number Field -->
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-black mb-2">Mobile Number</label>
                        <div class="phone-input-container flex items-center min-h-[44px]">
                            <img src="https://flagcdn.com/w40/ph.png" alt="Philippines" class="flag-icon w-5 h-3 sm:w-6 sm:h-4 mr-2">
                            <span class="country-code text-black mr-2 text-sm sm:text-base">+63</span>
                            <div class="separator-line w-px h-6 bg-black mr-2"></div>
                            <input type="tel" id="mobile_number" name="mobile_number" required 
                                   class="flex-1 px-2 sm:px-4 py-2 border {{ $errors->has('mobile_number') ? 'border-red-500' : 'border-black' }} bg-white text-black rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors duration-200 placeholder-gray-500 text-sm sm:text-base"
                                   placeholder="912 345 6789"
                                   maxlength="13"
                                   value="{{ old('mobile_number') }}">
                        </div>
                        @error('mobile_number')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        <p class="text-xs text-gray-600 mt-1">Enter your 10-digit mobile number (e.g., 912 345 6789)</p>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-black mb-2">Email</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-3 sm:px-4 py-3 border {{ $errors->has('email') ? 'border-red-500' : 'border-black' }} bg-white text-black rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors duration-200 placeholder-gray-500 min-h-[44px] text-sm sm:text-base"
                               placeholder="Enter email address"
                               value="{{ old('email') }}">
                        @error('email')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field (Optional for admin-created accounts) -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-black mb-2">Password (Optional)</label>
                        <input type="password" id="password" name="password" 
                               class="w-full px-3 sm:px-4 py-3 border {{ $errors->has('password') ? 'border-red-500' : 'border-black' }} bg-white text-black rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors duration-200 placeholder-gray-500 min-h-[44px] text-sm sm:text-base"
                               placeholder="Leave blank to let user set password later"
                               value="{{ old('password') }}">
                        @error('password')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        <p class="text-xs text-gray-600 mt-1">If provided, user can login immediately. If left blank, user will need to set password later.</p>
                    </div>

                    <!-- Password Confirmation Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-black mb-2">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="w-full px-3 sm:px-4 py-3 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-black' }} bg-white text-black rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors duration-200 placeholder-gray-500 min-h-[44px] text-sm sm:text-base"
                               placeholder="Confirm password"
                               value="{{ old('password_confirmation') }}">
                        @error('password_confirmation')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role Field (Hidden - Members are always created as 'member') -->
                    <input type="hidden" name="role" value="member">

                    <!-- Submit and Cancel Buttons -->
                    <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 pt-4">
                        <button type="submit" 
                                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center gap-2 min-h-[44px]">
                                Create
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                        <a href="{{ route('members.index') }}" 
                           class="w-full sm:w-auto px-6 py-3 border border-black text-black rounded-lg font-medium hover:bg-gray-100 transition-colors duration-200 text-center min-h-[44px] flex items-center justify-center">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('mobile_number');
            
            // Format phone number as user types
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
                
                // Limit to 10 digits
                if (value.length > 10) {
                    value = value.substring(0, 10);
                }
                
                // Format as XXX XXX XXXX
                if (value.length >= 6) {
                    value = value.substring(0, 3) + ' ' + value.substring(3, 6) + ' ' + value.substring(6);
                } else if (value.length >= 3) {
                    value = value.substring(0, 3) + ' ' + value.substring(3);
                }
                
                e.target.value = value;
            });
            
            // Prevent non-numeric input
            phoneInput.addEventListener('keydown', function(e) {
                // Allow backspace, delete, arrow keys, tab, etc.
                if ([8, 9, 27, 46, 37, 38, 39, 40].indexOf(e.keyCode) !== -1 ||
                    // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode === 67 && e.ctrlKey === true) ||
                    (e.keyCode === 86 && e.ctrlKey === true) ||
                    (e.keyCode === 88 && e.ctrlKey === true)) {
                    return;
                }
                
                // Allow only digits
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
            
            // Validate phone number on form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                const phoneValue = phoneInput.value.replace(/\D/g, '');
                
                if (phoneValue.length !== 10) {
                    e.preventDefault();
                    alert('Please enter a valid 10-digit Philippine mobile number. Current length: ' + phoneValue.length);
                    phoneInput.focus();
                    return false;
                }
                
                // Check if it starts with 9 (Philippine mobile numbers start with 9)
                if (!phoneValue.startsWith('9')) {
                    e.preventDefault();
                    alert('Philippine mobile numbers must start with 9. Current value: ' + phoneValue);
                    phoneInput.focus();
                    return false;
                }
            });
        });

        // Name validation for first name and last name
        const firstNameInput = document.getElementById('first_name');
        const lastNameInput = document.getElementById('last_name');
        const firstNameError = document.getElementById('first_name_error');
        const lastNameError = document.getElementById('last_name_error');

        function validateName(input, errorElement, fieldName) {
            const value = input.value.trim();
            const namePattern = /^[a-zA-Z\s\-]+$/;
            
            if (value && !namePattern.test(value)) {
                errorElement.textContent = `${fieldName} can only contain letters, spaces, and hyphens. Numbers and special characters are not allowed.`;
                errorElement.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            } else {
                errorElement.classList.add('hidden');
                input.classList.remove('border-red-500');
                return true;
            }
        }

        firstNameInput.addEventListener('input', function() {
            validateName(this, firstNameError, 'First name');
        });

        lastNameInput.addEventListener('input', function() {
            validateName(this, lastNameError, 'Last name');
        });

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const firstNameValid = validateName(firstNameInput, firstNameError, 'First name');
            const lastNameValid = validateName(lastNameInput, lastNameError, 'Last name');
            
            if (!firstNameValid || !lastNameValid) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</x-layout>