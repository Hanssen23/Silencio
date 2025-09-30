// Modern Registration Form JavaScript

class RegistrationForm {
    constructor() {
        this.form = document.querySelector('.registration-form');
        this.passwordInput = document.querySelector('input[name="password"]');
        this.confirmPasswordInput = document.querySelector('input[name="password_confirmation"]');
        this.emailInput = document.querySelector('input[name="email"]');
        this.mobileInput = document.querySelector('input[name="mobile_number"]');
        this.submitButton = document.querySelector('.submit-button');
        
        this.init();
    }
    
    init() {
        if (!this.form) return;
        
        this.setupPasswordVisibility();
        this.setupPasswordStrength();
        this.setupRealTimeValidation();
        this.setupFormSubmission();
        this.setupPhoneFormatting();
        this.setupNameValidation();
    }
    
    setupPasswordVisibility() {
        const passwordFields = document.querySelectorAll('input[type="password"]');
        
        passwordFields.forEach(field => {
            const container = field.closest('.form-group');
            if (!container) return;
            
            // Create password toggle button
            const toggleButton = document.createElement('button');
            toggleButton.type = 'button';
            toggleButton.className = 'password-toggle';
            toggleButton.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            `;
            
            // Wrap input in container
            const inputContainer = document.createElement('div');
            inputContainer.className = 'password-input-container';
            field.parentNode.insertBefore(inputContainer, field);
            inputContainer.appendChild(field);
            inputContainer.appendChild(toggleButton);
            
            // Toggle functionality
            toggleButton.addEventListener('click', () => {
                const isPassword = field.type === 'password';
                field.type = isPassword ? 'text' : 'password';
                
                // Update icon
                toggleButton.innerHTML = isPassword ? `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                ` : `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                `;
            });
        });
    }
    
    setupPasswordStrength() {
        if (!this.passwordInput) return;
        
        const strengthContainer = document.createElement('div');
        strengthContainer.className = 'password-strength';
        
        // Create strength bars
        for (let i = 0; i < 4; i++) {
            const bar = document.createElement('div');
            bar.className = 'strength-bar';
            strengthContainer.appendChild(bar);
        }
        
        const strengthText = document.createElement('div');
        strengthText.className = 'strength-text';
        strengthContainer.appendChild(strengthText);
        
        this.passwordInput.parentNode.parentNode.appendChild(strengthContainer);
        
        this.passwordInput.addEventListener('input', () => {
            const password = this.passwordInput.value;
            const strength = this.calculatePasswordStrength(password);
            this.updatePasswordStrength(strength, strengthContainer);
        });
    }
    
    calculatePasswordStrength(password) {
        let score = 0;
        let feedback = '';
        
        if (password.length >= 6) score++; // Changed from 8 to 6 for member registration
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;
        
        if (password.length < 6) {
            return { level: 'weak', score: 0, feedback: 'Too short (minimum 6 characters)' };
        } else if (score < 3) {
            return { level: 'weak', score: 1, feedback: 'Weak password' };
        } else if (score < 4) {
            return { level: 'fair', score: 2, feedback: 'Fair password' };
        } else if (score < 5) {
            return { level: 'good', score: 3, feedback: 'Good password' };
        } else {
            return { level: 'strong', score: 4, feedback: 'Strong password' };
        }
    }
    
    updatePasswordStrength(strength, container) {
        const bars = container.querySelectorAll('.strength-bar');
        const text = container.querySelector('.strength-text');
        
        // Reset all bars
        bars.forEach(bar => {
            bar.className = 'strength-bar';
        });
        
        // Update bars based on strength
        for (let i = 0; i < strength.score; i++) {
            bars[i].classList.add(strength.level);
        }
        
        // Update text
        text.textContent = strength.feedback;
        text.className = `strength-text ${strength.level}`;
    }
    
    setupRealTimeValidation() {
        // Email validation
        if (this.emailInput) {
            this.emailInput.addEventListener('blur', () => {
                this.validateEmail();
            });
        }
        
        // Password confirmation validation
        if (this.confirmPasswordInput) {
            this.confirmPasswordInput.addEventListener('input', () => {
                this.validatePasswordConfirmation();
            });
        }
        
        // Mobile number validation
        if (this.mobileInput) {
            this.mobileInput.addEventListener('blur', () => {
                this.validateMobileNumber();
            });
        }
        
        // Real-time validation for all inputs
        const inputs = this.form.querySelectorAll('input[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
            
            input.addEventListener('input', () => {
                // Clear error state on input
                this.clearFieldError(input);
            });
        });
    }
    
    validateEmail() {
        const email = this.emailInput.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            this.showFieldError(this.emailInput, 'Please enter a valid email address');
            return false;
        } else {
            this.clearFieldError(this.emailInput);
            return true;
        }
    }
    
    validatePasswordConfirmation() {
        const password = this.passwordInput.value;
        const confirmPassword = this.confirmPasswordInput.value;
        
        if (confirmPassword && password !== confirmPassword) {
            this.showFieldError(this.confirmPasswordInput, 'Passwords do not match');
            return false;
        } else {
            this.clearFieldError(this.confirmPasswordInput);
            return true;
        }
    }
    
    validateMobileNumber() {
        const mobile = this.mobileInput.value;
        const mobileRegex = /^9\d{2}\s\d{3}\s\d{4}$/;
        
        if (mobile && !mobileRegex.test(mobile)) {
            this.showFieldError(this.mobileInput, 'Please enter a valid 10-digit mobile number (e.g., 912 345 6789)');
            return false;
        } else {
            this.clearFieldError(this.mobileInput);
            return true;
        }
    }
    
    validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        
        if (!value && field.hasAttribute('required')) {
            this.showFieldError(field, `${this.getFieldLabel(fieldName)} is required`);
            return false;
        }
        
        // Additional validations based on field type
        if (field.type === 'email' && value) {
            return this.validateEmail();
        }
        
        if (fieldName === 'mobile_number' && value) {
            return this.validateMobileNumber();
        }
        
        this.clearFieldError(field);
        return true;
    }
    
    getFieldLabel(fieldName) {
        const labels = {
            'first_name': 'First name',
            'last_name': 'Last name',
            'email': 'Email',
            'mobile_number': 'Mobile number',
            'password': 'Password',
            'password_confirmation': 'Confirm password'
        };
        return labels[fieldName] || fieldName;
    }
    
    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('error');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `
            <svg class="error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            ${message}
        `;
        
        field.parentNode.appendChild(errorDiv);
    }
    
    clearFieldError(field) {
        field.classList.remove('error');
        
        const existingError = field.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
    }
    
    setupPhoneFormatting() {
        if (!this.mobileInput) return;
        
        this.mobileInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            
            // Format as XXX XXX XXXX
            if (value.length >= 7) {
                value = value.substring(0, 3) + ' ' + value.substring(3, 6) + ' ' + value.substring(6);
            } else if (value.length >= 4) {
                value = value.substring(0, 3) + ' ' + value.substring(3);
            }
            
            e.target.value = value;
        });
    }
    
    setupFormSubmission() {
        if (!this.form) return;
        
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Validate all fields
            const inputs = this.form.querySelectorAll('input[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!this.validateField(input)) {
                    isValid = false;
                }
            });
            
            // Additional validations
            if (this.emailInput && !this.validateEmail()) isValid = false;
            if (this.confirmPasswordInput && !this.validatePasswordConfirmation()) isValid = false;
            if (this.mobileInput && !this.validateMobileNumber()) isValid = false;
            
            if (!isValid) {
                // Scroll to first error
                const firstError = this.form.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return;
            }
            
            // Show loading state
            this.showLoadingState();
            
            // Submit form
            this.form.submit();
        });
    }
    
    showLoadingState() {
        this.form.classList.add('loading');
        this.submitButton.disabled = true;
        this.submitButton.textContent = 'Creating Account...';
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new RegistrationForm();
});

// Additional utility functions
function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length > 10) value = value.substring(0, 10);
    
    if (value.length >= 7) {
        value = value.substring(0, 3) + ' ' + value.substring(3, 6) + ' ' + value.substring(6);
    } else if (value.length >= 4) {
        value = value.substring(0, 3) + ' ' + value.substring(3);
    }
    
    input.value = value;
}

// Accessibility improvements
document.addEventListener('keydown', (e) => {
    // Allow Enter key to submit form
    if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
        const form = e.target.closest('form');
        if (form) {
            const submitButton = form.querySelector('.submit-button');
            if (submitButton && !submitButton.disabled) {
                submitButton.click();
            }
        }
    }

    setupNameValidation() {
        const nameFields = document.querySelectorAll('input[name="first_name"], input[name="last_name"]');
        
        nameFields.forEach(field => {
            field.addEventListener('input', (e) => {
                // Remove any non-letter characters and spaces
                let value = e.target.value;
                value = value.replace(/[^A-Za-z\s]/g, '');
                
                // Prevent multiple consecutive spaces
                value = value.replace(/\s+/g, ' ');
                
                // Update the field value
                e.target.value = value;
            });
            
            field.addEventListener('keypress', (e) => {
                // Prevent numbers and special characters
                const char = String.fromCharCode(e.which);
                if (!/[A-Za-z\s]/.test(char)) {
                    e.preventDefault();
                }
            });
            
            field.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const cleanPaste = paste.replace(/[^A-Za-z\s]/g, '').replace(/\s+/g, ' ');
                e.target.value = cleanPaste;
            });
        });
    }
});

// Focus management for better accessibility
document.addEventListener('DOMContentLoaded', () => {
    const firstInput = document.querySelector('.form-input');
    if (firstInput) {
        firstInput.focus();
    }
});
