<!-- Member Delete Confirmation Modal -->
<div id="memberDeleteConfirmModal" class="fixed inset-0 flex items-center justify-center p-2 sm:p-4 hidden z-50">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full transform transition-all duration-300 scale-95 opacity-0 border border-gray-200" 
         id="memberDeleteConfirmModalContent"
         style="box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
        <div class="p-4 sm:p-6 text-center">
            <div class="mb-4 sm:mb-6">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Are you sure you want to delete this member?</h3>
                <p id="memberDeleteConfirmMessage" class="text-sm sm:text-base text-gray-600">This action cannot be undone.</p>
            </div>
            <div class="flex flex-col sm:flex-row justify-center gap-3 pt-4" style="background-color: #F9FAFB;">
                <button onclick="cancelMemberDelete()" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gray-100 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium">
                    Cancel
                </button>
                <button onclick="confirmMemberDelete()" class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 font-medium">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let pendingMemberDelete = null;

    function deleteMember(memberId, memberName) {
        const modal = document.getElementById('memberDeleteConfirmModal');
        const content = document.getElementById('memberDeleteConfirmModalContent');

        // Store the member info for deletion
        pendingMemberDelete = {
            id: memberId,
            name: memberName
        };
        
        // Update modal message
        document.getElementById('memberDeleteConfirmMessage').textContent = `Are you sure you want to delete "${memberName}"? This action cannot be undone.`;
        
        // Show confirmation modal
        modal.classList.remove('hidden');
        
        // Trigger animation
        setTimeout(function() {
            content.style.transform = 'scale(1)';
            content.style.opacity = '1';
        }, 10);
    }

    function cancelMemberDelete() {
        const modal = document.getElementById('memberDeleteConfirmModal');
        const content = document.getElementById('memberDeleteConfirmModalContent');
        
        // Trigger exit animation
        content.style.transform = 'scale(0.95)';
        content.style.opacity = '0';
        
        // Hide modal after animation
        setTimeout(function() {
            modal.classList.add('hidden');
            pendingMemberDelete = null;
        }, 300);
    }

    function confirmMemberDelete() {
        if (pendingMemberDelete) {
            // Create a form and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            
            // Determine if we're in employee or admin panel
            const isEmployee = window.location.pathname.includes('/employee');
            form.action = isEmployee ? `/employee/members/${pendingMemberDelete.id}` : `/members/${pendingMemberDelete.id}`;
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            // Add method spoofing for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Append form to body and submit
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('memberDeleteConfirmModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    cancelMemberDelete();
                }
            });
        }
    });
</script>
