<x-layout body-class="accounts-page">
    <x-nav></x-nav>
    <div class="flex-1 bg-white">
        <x-topbar>Accounts</x-topbar>

        <div class="bg-white p-4 sm:p-6">
            <!-- Account Creation Form -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-white rounded-lg border p-4 sm:p-6 lg:p-8" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6" style="color: #1E40AF;">Create New Account</h2>
                    <form id="create-account-form" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 sm:gap-4">
                        <div>
                            <label for="account-first-name" class="block text-sm font-medium mb-2" style="color: #6B7280; font-size: 0.875rem;">First Name</label>
                            <input type="text" id="account-first-name" name="first_name" required
                                   class="w-full px-3 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   style="border-color: #E5E7EB; background-color: #F9FAFB; color: #000000; font-size: 1rem;"
                                   placeholder="Enter first name">
                            <div id="first-name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="account-last-name" class="block text-sm font-medium mb-2" style="color: #6B7280; font-size: 0.875rem;">Last Name</label>
                            <input type="text" id="account-last-name" name="last_name" required
                                   class="w-full px-3 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   style="border-color: #E5E7EB; background-color: #F9FAFB; color: #000000; font-size: 1rem;"
                                   placeholder="Enter last name">
                            <div id="last-name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="account-email" class="block text-sm font-medium mb-2" style="color: #6B7280; font-size: 0.875rem;">Email</label>
                            <input type="email" id="account-email" name="email" required
                                   class="w-full px-3 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   style="border-color: #E5E7EB; background-color: #F9FAFB; color: #000000; font-size: 1rem;"
                                   placeholder="Enter email address">
                            <div id="email-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="account-mobile-number" class="block text-sm font-medium mb-2" style="color: #6B7280; font-size: 0.875rem;">Mobile Number</label>
                            <div class="phone-input-container">
                                <img src="https://flagcdn.com/w40/ph.png" alt="Philippines" class="flag-icon w-6 h-4">
                                <span class="country-code">+63</span>
                                <div class="separator-line"></div>
                                <input type="tel" id="account-mobile-number" name="mobile_number"
                                       class="phone-input"
                                       placeholder="912 345 6789" 
                                       maxlength="13">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Enter your 10-digit mobile number (e.g., 912 345 6789)</p>
                            <div id="mobile-number-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="account-password" class="block text-sm font-medium mb-2" style="color: #6B7280; font-size: 0.875rem;">Password</label>
                            <input type="password" id="account-password" name="password" required
                                   class="w-full px-3 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   style="border-color: #E5E7EB; background-color: #F9FAFB; color: #000000; font-size: 1rem;"
                                   placeholder="Enter password">
                            <div id="password-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="account-password-confirmation" class="block text-sm font-medium mb-2" style="color: #6B7280; font-size: 0.875rem;">Confirm Password</label>
                            <input type="password" id="account-password-confirmation" name="password_confirmation" required
                                   class="w-full px-3 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   style="border-color: #E5E7EB; background-color: #F9FAFB; color: #000000; font-size: 1rem;"
                                   placeholder="Confirm password">
                            <div id="password-confirmation-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                        
                        <div>
                            <label for="account-role" class="block text-sm font-medium mb-2" style="color: #6B7280; font-size: 0.875rem;">User Type</label>
                            <select id="account-role" name="role" required
                                    class="w-full px-3 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    style="border-color: #E5E7EB; background-color: #F9FAFB; color: #000000; font-size: 1rem;">
                                <option value="">Select user type</option>
                                <option value="admin">Admin</option>
                                <option value="employee">Employee</option>
                            </select>
                            <div id="role-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                        
                        <div class="sm:col-span-2 lg:col-span-3 xl:col-span-5 flex justify-end">
                            <button type="submit" id="create-account-btn"
                                    class="w-full sm:w-auto px-4 sm:px-6 py-3 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center gap-2"
                                    style="background-color: #2563EB; border-radius: 6px;">
                                <span id="create-account-text">Create Account</span>
                                <div id="create-account-spinner" class="hidden">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Account Modal -->
            <div id="editAccountModal" class="fixed inset-0 flex items-center justify-center p-2 sm:p-4 hidden z-50">
                <div id="editAccountModalContent" class="bg-white rounded-xl shadow-lg max-w-md w-full max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300 border border-gray-200" style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200" style="background-color: #1E40AF;">
                        <h3 class="text-lg sm:text-xl font-bold text-white">Edit Account</h3>
                        <button onclick="closeEditAccountModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-colors">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                        <form id="editAccountForm" class="p-4 sm:p-6">
                            <input type="hidden" name="account_id" id="editAccountId">
                            <div class="mb-4">
                                <label class="block text-sm font-semibold mb-2" style="color: #374151;">First Name</label>
                                <input type="text" name="first_name" id="editAccountFirstName" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" style="border-color: #E5E7EB;" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-semibold mb-2" style="color: #374151;">Last Name</label>
                                <input type="text" name="last_name" id="editAccountLastName" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" style="border-color: #E5E7EB;" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-semibold mb-2" style="color: #374151;">Email</label>
                                <input type="email" name="email" id="editAccountEmail" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" style="border-color: #E5E7EB;" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-semibold mb-2" style="color: #374151;">Mobile Number</label>
                                <div class="phone-input-container">
                                    <img src="https://flagcdn.com/w40/ph.png" alt="Philippines" class="flag-icon w-6 h-4">
                                    <span class="country-code">+63</span>
                                    <div class="separator-line"></div>
                                    <input type="tel" name="mobile_number" id="editAccountMobileNumber" 
                                           class="phone-input" 
                                           placeholder="912 345 6789" 
                                           maxlength="13">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Enter your 10-digit mobile number (e.g., 912 345 6789)</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-semibold mb-2" style="color: #374151;">Change Password (leave blank to keep current)</label>
                                <input type="password" name="password" id="editAccountPassword" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" style="border-color: #E5E7EB;" placeholder="Enter new password">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-semibold mb-2" style="color: #374151;">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="editAccountPasswordConfirmation" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" style="border-color: #E5E7EB;" placeholder="Confirm new password">
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-semibold mb-2" style="color: #374151;">User Type</label>
                                <select name="role" id="editAccountRole" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" style="border-color: #E5E7EB;" required>
                                    <option value="admin">Admin</option>
                                    <option value="employee">Employee</option>
                                </select>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4" style="background-color: #F9FAFB;">
                                <button type="button" onclick="closeEditAccountModal()" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gray-100 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                                    Cancel
                                </button>
                                <button type="submit" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium">
                                    Update Account
                                </button>
                            </div>
                        </form>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div id="deleteConfirmModal" class="fixed inset-0 flex items-center justify-center p-2 sm:p-4 hidden z-50">
                <div class="bg-white rounded-xl shadow-lg max-w-md w-full transform transition-all duration-300 scale-95 opacity-0 border border-gray-200" 
                     id="deleteConfirmModalContent"
                     style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
                    <div class="p-4 sm:p-6 text-center">
                        <div class="mb-4 sm:mb-6">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Are you sure you want to delete this account?</h3>
                            <p id="deleteConfirmMessage" class="text-sm sm:text-base text-gray-600">This action cannot be undone.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-center gap-3 pt-4" style="background-color: #F9FAFB;">
                            <button onclick="cancelDelete()" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gray-100 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                                Cancel
                            </button>
                            <button onclick="confirmDelete()" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 font-medium">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Management Features -->
            <div class="space-y-6">
                <!-- Search and Filter Controls -->
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <div class="relative flex-1">
                            <input type="text" id="accounts-search" placeholder="Search accounts..."
                                   class="w-full px-3 py-2 sm:py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   style="border-color: #E5E7EB; background-color: #F9FAFB; color: #000000; font-size: 0.875rem; sm:font-size: 1rem;">
                        </div>
                        
                        <div class="relative w-full sm:w-auto">
                            <select id="accounts-role-filter" class="w-full sm:w-auto px-3 py-2 sm:py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    style="border-color: #E5E7EB; background-color: #F9FAFB; color: #000000; font-size: 0.875rem; sm:font-size: 1rem;">
                                <option value="">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="employee">Employee</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Accounts Table -->
                <div class="bg-white rounded-lg border overflow-hidden" style="border-color: #E5E7EB; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[600px]">
                            <thead class="bg-gray-50" style="background-color: #F9FAFB;">
                                <tr>
                                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="color: #6B7280; font-size: 0.75rem; font-weight: 500;">
                                        ACCOUNT
                                    </th>
                                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="color: #6B7280; font-size: 0.75rem; font-weight: 500;">
                                        USER TYPE
                                    </th>
                                    <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="color: #6B7280; font-size: 0.75rem; font-weight: 500;">
                                        ACTIONS
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="accounts-table-body" class="bg-white divide-y divide-gray-200" style="background-color: #FFFFFF;">
                                <!-- Dynamic content will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom styles for the accounts page */
        .action-button-activate {
            color: #059669;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .action-button-edit {
            color: #2563EB;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .action-button-delete {
            color: #DC2626;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        /* Phone Input Container Styles */
        .phone-input-container {
            position: relative;
            display: flex;
            align-items: center;
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .phone-input-container:focus-within {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .phone-input-container .flag-icon {
            width: 24px;
            height: 16px;
            border-radius: 2px;
            margin-right: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .phone-input-container .country-code {
            font-weight: 500;
            color: #374151;
            margin-right: 0.5rem;
            font-size: 0.875rem;
        }
        
        .phone-input-container .separator-line {
            width: 1px;
            height: 20px;
            background-color: #D1D5DB;
            margin-right: 0.75rem;
        }
        
        .phone-input-container .phone-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 1rem;
            color: #000000;
            background: transparent;
            padding: 0;
        }
        
        .phone-input-container .phone-input::placeholder {
            color: #9CA3AF;
        }
        
        .phone-input-container.error {
            border-color: #DC2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
    </style>

    <script>
        // ===== ACCOUNTS MANAGEMENT FUNCTIONALITY =====
        
        // Accounts management variables
        let accountsData = [];
        let currentAccountsPage = 1;
        let accountsTotalPages = 1;
        let accountsRefreshInterval = null;
        let currentPage = 1;

        // Initialize accounts management
        function initializeAccountsManagement() {
            loadAccounts();
            setupEventListeners();
            setupPhoneFormatting();
        }

        // Setup event listeners
        function setupEventListeners() {
            // Create account form
            const createForm = document.getElementById('create-account-form');
            if (createForm) {
                createForm.addEventListener('submit', handleCreateAccount);
            }

            // Search functionality
            const searchInput = document.getElementById('accounts-search');
            if (searchInput) {
                searchInput.addEventListener('input', debounce(handleSearch, 300));
            }

            // Role filter
            const roleFilter = document.getElementById('accounts-role-filter');
            if (roleFilter) {
                roleFilter.addEventListener('change', handleRoleFilter);
            }
        }

        // Setup phone number formatting
        function setupPhoneFormatting() {
            const phoneInputs = document.querySelectorAll('.phone-input');
            
            phoneInputs.forEach(input => {
                // Format phone number as user types
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
                    
                    // Limit to 10 digits (Philippine mobile numbers)
                    if (value.length > 10) {
                        value = value.substring(0, 10);
                    }
                    
                    // Format with spaces: 912 345 6789
                    if (value.length > 0) {
                        if (value.length <= 3) {
                            value = value;
                        } else if (value.length <= 6) {
                            value = value.substring(0, 3) + ' ' + value.substring(3);
                        } else {
                            value = value.substring(0, 3) + ' ' + value.substring(3, 6) + ' ' + value.substring(6);
                        }
                    }
                    
                    e.target.value = value;
                });
                
                // Prevent user from entering non-digits
                input.addEventListener('keydown', function(e) {
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
            });
        }

        // Handle create account form submission
        async function handleCreateAccount(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const accountData = {
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name'),
                email: formData.get('email'),
                mobile_number: formData.get('mobile_number'),
                password: formData.get('password'),
                password_confirmation: formData.get('password_confirmation'),
                role: formData.get('role')
            };

            // Clear previous errors
            clearFormErrors();

            // Validate form
            const errors = validateAccountForm(accountData);
            if (Object.keys(errors).length > 0) {
                displayFormErrors(errors);
                return;
            }

            // Show loading state
            setCreateButtonLoading(true);

            try {
                console.log('Creating account with data:', accountData);
                const response = await fetch('/accounts', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(accountData)
                });

                console.log('Response status:', response.status);
                
                // Check if response is HTML (redirect to login)
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('text/html')) {
                    console.log('Received HTML response, likely redirected to login');
                    showNotification('Please log in to create accounts', 'error');
                    return;
                }
                
                const result = await response.json();
                console.log('Response data:', result);

                if (response.ok) {
                    showNotification('Account created successfully!', 'success');
                    e.target.reset();
                    loadAccounts(); // Refresh the accounts list
                } else {
                    if (result.errors) {
                        displayFormErrors(result.errors);
                    } else {
                        showNotification(result.message || 'Failed to create account', 'error');
                    }
                }
            } catch (error) {
                console.error('Error creating account:', error);
                if (error.message.includes('Unexpected token')) {
                    showNotification('Authentication required. Please refresh the page and log in.', 'error');
                } else {
                    showNotification('An error occurred while creating the account', 'error');
                }
            } finally {
                setCreateButtonLoading(false);
            }
        }

        // Validate account form
        function validateAccountForm(data) {
            const errors = {};

            if (!data.first_name || data.first_name.trim().length < 2) {
                errors.first_name = ['First name must be at least 2 characters long'];
            }

            if (!data.last_name || data.last_name.trim().length < 2) {
                errors.last_name = ['Last name must be at least 2 characters long'];
            }

            if (!data.email || !isValidEmail(data.email)) {
                errors.email = ['Please enter a valid email address'];
            }

            // Validate mobile number if provided
            if (data.mobile_number && data.mobile_number.trim()) {
                const mobileRegex = /^9\d{2}\s\d{3}\s\d{4}$/;
                if (!mobileRegex.test(data.mobile_number)) {
                    errors.mobile_number = ['Please enter a valid 10-digit Philippine mobile number (e.g., 912 345 6789)'];
                }
            }

            if (!data.password || data.password.length < 8) {
                errors.password = ['Password must be at least 8 characters long'];
            }

            if (data.password !== data.password_confirmation) {
                errors.password_confirmation = ['Passwords do not match'];
            }

            if (!data.role) {
                errors.role = ['Please select a user type'];
            }

            return errors;
        }

        // Check if email is valid
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Clear form errors
        function clearFormErrors() {
            document.querySelectorAll('[id$="-error"]').forEach(errorEl => {
                errorEl.classList.add('hidden');
                errorEl.textContent = '';
            });
            
            // Reset field borders
            document.querySelectorAll('#create-account-form input, #create-account-form select').forEach(field => {
                field.style.borderColor = '#E5E7EB';
            });
        }

        // Display form errors
        function displayFormErrors(errors) {
            Object.keys(errors).forEach(field => {
                const errorElement = document.getElementById(`${field.replace('_', '-')}-error`);
                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                    errorElement.classList.remove('hidden');
                }
                
                // Add red border to the field
                const fieldElement = document.getElementById(`account-${field.replace('_', '-')}`);
                if (fieldElement) {
                    fieldElement.style.borderColor = '#DC2626';
                }
            });
        }

        // Set create button loading state
        function setCreateButtonLoading(loading) {
            const button = document.getElementById('create-account-btn');
            const text = document.getElementById('create-account-text');
            const spinner = document.getElementById('create-account-spinner');

            if (loading) {
                button.disabled = true;
                text.textContent = 'Creating...';
                spinner.classList.remove('hidden');
            } else {
                button.disabled = false;
                text.textContent = 'Create Account';
                spinner.classList.add('hidden');
            }
        }

        // Handle search
        function handleSearch(e) {
            const searchTerm = e.target.value.toLowerCase();
            filterAccounts(searchTerm);
        }

        // Handle role filter
        function handleRoleFilter(e) {
            const selectedRole = e.target.value;
            filterAccounts(null, selectedRole);
        }

        // Filter accounts
        function filterAccounts(searchTerm = null, roleFilter = null) {
            const rows = document.querySelectorAll('#accounts-table-body tr');
            
            rows.forEach(row => {
                const nameElement = row.querySelector('.text-sm.font-medium');
                const emailElement = row.querySelector('.text-sm.text-gray-500');
                const roleElement = row.querySelector('span');
                
                if (!nameElement || !emailElement || !roleElement) return;
                
                const name = nameElement.textContent.toLowerCase();
                const email = emailElement.textContent.toLowerCase();
                const role = roleElement.textContent.toLowerCase();
                
                let showRow = true;
                
                if (searchTerm && !name.includes(searchTerm) && !email.includes(searchTerm)) {
                    showRow = false;
                }
                
                if (roleFilter && role !== roleFilter.toLowerCase()) {
                    showRow = false;
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        }

        // Load accounts from server
        async function loadAccounts() {
            try {
                console.log('Loading accounts...');
                const response = await fetch('/accounts', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Load accounts response status:', response.status);
                
                // Check if response is HTML (redirect to login)
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('text/html')) {
                    console.log('Received HTML response, likely redirected to login');
                    showNotification('Please log in to view accounts', 'error');
                    return;
                }
                
                const result = await response.json();
                console.log('Load accounts response data:', result);
                
                if (response.ok) {
                    accountsData = result.accounts || [];
                    renderAccountsTable();
                } else {
                    console.error('Failed to load accounts:', result);
                    showNotification('Failed to load accounts: ' + (result.message || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error('Error loading accounts:', error);
                if (error.message.includes('Unexpected token')) {
                    showNotification('Authentication required. Please refresh the page and log in.', 'error');
                } else {
                    showNotification('Failed to load accounts: ' + error.message, 'error');
                }
            }
        }

        // Render accounts table
        function renderAccountsTable() {
            const tbody = document.getElementById('accounts-table-body');
            tbody.innerHTML = '';

            if (accountsData.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            No accounts found
                        </td>
                    </tr>
                `;
                return;
            }

            accountsData.forEach(account => {
                const isActive = account.email_verified_at !== null;
                const firstName = account.first_name || '';
                const lastName = account.last_name || '';
                const initials = (firstName.charAt(0) + lastName.charAt(0)).toUpperCase() || account.name.substring(0, 2).toUpperCase();
                const fullName = `${firstName} ${lastName}`.trim() || account.name;
                const roleColor = account.role === 'admin' ? '#8B5CF6' : '#10B981';
                
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                row.style.backgroundColor = '#FFFFFF';
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-medium" style="background-color: #3B82F6;">
                                    ${initials}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900" style="color: #111827; font-size: 0.875rem; font-weight: 500;">
                                    ${fullName}
                                </div>
                                <div class="text-sm text-gray-500" style="color: #6B7280; font-size: 0.875rem;">
                                    ${account.email}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full" style="background-color: ${roleColor}; color: #FFFFFF; font-size: 0.75rem; font-weight: 600;">
                            ${account.role.charAt(0).toUpperCase() + account.role.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-4">
                            ${account.role === 'admin' ? `
                                <button onclick="editAccount(${account.id})" class="text-blue-600 hover:text-blue-900" style="color: #2563EB; font-size: 0.875rem; font-weight: 500;">
                                    Edit
                                </button>
                            ` : `
                                <button onclick="toggleAccountStatus(${account.id})" class="text-green-600 hover:text-green-900" style="color: ${isActive ? '#DC2626' : '#059669'}; font-size: 0.875rem; font-weight: 500;">
                                    ${isActive ? 'Deactivate' : 'Activate'}
                                </button>
                                <button onclick="editAccount(${account.id})" class="text-blue-600 hover:text-blue-900" style="color: #2563EB; font-size: 0.875rem; font-weight: 500;">
                                    Edit
                                </button>
                                <button onclick="deleteAccount(${account.id})" class="text-red-600 hover:text-red-900" style="color: #DC2626; font-size: 0.875rem; font-weight: 500;">
                                    Delete
                                </button>
                            `}
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-md shadow-lg text-white max-w-sm ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'}`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Edit account function
        function editAccount(accountId) {
            const account = accountsData.find(acc => acc.id == accountId);
            if (!account) return;

            // Populate edit modal
            document.getElementById('editAccountId').value = account.id;
            document.getElementById('editAccountFirstName').value = account.first_name || '';
            document.getElementById('editAccountLastName').value = account.last_name || '';
            document.getElementById('editAccountEmail').value = account.email;
            
            // Format mobile number for display
            let mobileNumber = account.mobile_number || '';
            if (mobileNumber) {
                // Remove +63 prefix and format as 912 345 6789
                mobileNumber = mobileNumber.replace(/^\+63/, '').replace(/\D/g, '');
                if (mobileNumber.length === 10) {
                    mobileNumber = mobileNumber.substring(0, 3) + ' ' + mobileNumber.substring(3, 6) + ' ' + mobileNumber.substring(6);
                }
            }
            document.getElementById('editAccountMobileNumber').value = mobileNumber;
            
            document.getElementById('editAccountPassword').value = '';
            document.getElementById('editAccountPasswordConfirmation').value = '';
            document.getElementById('editAccountRole').value = account.role;

            // Show modal
            const modal = document.getElementById('editAccountModal');
            const content = document.getElementById('editAccountModalContent');
            
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        // Close edit account modal
        function closeEditAccountModal() {
            const modal = document.getElementById('editAccountModal');
            const content = document.getElementById('editAccountModalContent');
            
            // Trigger close animation
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Toggle account status (activate/deactivate)
        async function toggleAccountStatus(accountId) {
            try {
                console.log('Toggling account status for ID:', accountId);
                const response = await fetch(`/accounts/${accountId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                const result = await response.json();
                console.log('Response data:', result);

                if (response.ok) {
                    showNotification(result.message, 'success');
                    loadAccounts(); // Refresh the accounts list
                } else {
                    showNotification(result.message || 'Failed to update account status', 'error');
                }
            } catch (error) {
                console.error('Error toggling account status:', error);
                showNotification('Failed to update account status', 'error');
            }
        }

        // Delete account function
        function deleteAccount(accountId) {
            const account = accountsData.find(acc => acc.id == accountId);
            if (!account) return;

            const modal = document.getElementById('deleteConfirmModal');
            const content = document.getElementById('deleteConfirmModalContent');

            // Store the account ID for deletion
            window.pendingDeleteAccountId = accountId;
            
            // Update modal message
            const firstName = account.first_name || '';
            const lastName = account.last_name || '';
            const fullName = `${firstName} ${lastName}`.trim() || account.name;
            document.getElementById('deleteConfirmMessage').textContent = `Are you sure you want to delete "${fullName}"? This action cannot be undone.`;
            
            // Show confirmation modal
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        // Confirmation modal functions
        function cancelDelete() {
            const modal = document.getElementById('deleteConfirmModal');
            const content = document.getElementById('deleteConfirmModalContent');
            
            // Trigger close animation
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                window.pendingDeleteAccountId = null;
            }, 300);
        }

        async function confirmDelete() {
            if (!window.pendingDeleteAccountId) return;

            try {
                console.log('Attempting to delete account ID:', window.pendingDeleteAccountId);
                
                const response = await fetch(`/accounts/${window.pendingDeleteAccountId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Delete response status:', response.status);
                console.log('Delete response ok:', response.ok);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Delete response error:', errorText);
                    showNotification('Failed to delete account: ' + response.status, 'error');
                    cancelDelete();
                    return;
                }

                const result = await response.json();
                console.log('Delete response data:', result);

                if (result.success) {
                    showNotification(result.message, 'success');
                    loadAccounts(); // Refresh the accounts list
                } else {
                    showNotification(result.message || 'Failed to delete account', 'error');
                }
            } catch (error) {
                console.error('Error deleting account:', error);
                showNotification('Network error: Unable to delete account', 'error');
            }

            cancelDelete();
        }

        // Handle edit account form submission
        document.getElementById('editAccountForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const accountId = formData.get('account_id');
            
            const data = {
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name'),
                email: formData.get('email'),
                mobile_number: formData.get('mobile_number'),
                role: formData.get('role')
            };

            // Only include password if provided
            if (formData.get('password')) {
                data.password = formData.get('password');
                data.password_confirmation = formData.get('password_confirmation');
            }

            console.log('Updating account with data:', data);
            console.log('Account ID:', accountId);

            try {
                const response = await fetch(`/accounts/${accountId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                });

                console.log('Update response status:', response.status);
                console.log('Update response headers:', response.headers);

                const result = await response.json();
                console.log('Update response data:', result);

                if (response.ok) {
                    showNotification('Account updated successfully', 'success');
                    closeEditAccountModal();
                    loadAccounts(); // Refresh the accounts list
                } else {
                    if (result.errors) {
                        // Display validation errors
                        Object.keys(result.errors).forEach(field => {
                            const errorElement = document.getElementById(`${field}-error`);
                            if (errorElement) {
                                errorElement.textContent = result.errors[field][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    } else {
                        showNotification(result.message || 'Failed to update account', 'error');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Failed to update account', 'error');
            }
        });

        // Initialize accounts management when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeAccountsManagement();
            
            // Add event listener for delete confirmation modal
            document.getElementById('deleteConfirmModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    cancelDelete();
                }
            });
            
            // Mobile number validation for account creation
            const phoneInput = document.getElementById('account-mobile-number');
            if (phoneInput) {
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
            }
        });

        // Clean up intervals when page is unloaded
        window.addEventListener('beforeunload', function() {
            if (accountsRefreshInterval) {
                clearInterval(accountsRefreshInterval);
            }
        });
    </script>
</x-layout>