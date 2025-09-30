<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Silencio System</title>

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        
        <!-- Dropdown Styles -->
        <link rel="stylesheet" href="{{ asset('css/dropdown.css') }}">
        <!-- Sidebar Styles -->
        <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    </head>
    <body class="m-0 p-0 h-screen w-screen {{ $bodyClass ?? '' }}">
        <!-- Mobile Sidebar Overlay -->
        <div id="sidebar-overlay" class="sidebar-overlay"></div>
        
        <main class="h-full w-full flex flex-col lg:flex-row">
            {{ $slot }}
        </main>
        
        <!-- Dropdown JavaScript -->
        <script src="{{ asset('js/dropdown.js') }}"></script>
        <!-- Sidebar JavaScript -->
        <script src="{{ asset('js/sidebar.js') }}"></script>
        <!-- Real-time Features JavaScript -->
        <script src="{{ asset('js/realtime.js') }}"></script>
        
        <!-- Mobile Sidebar Toggle Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebarToggle = document.querySelector('.sidebar-toggle');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                
                if (sidebarToggle && sidebar && overlay) {
                    sidebarToggle.addEventListener('click', function() {
                        sidebar.classList.toggle('open');
                        overlay.classList.toggle('active');
                    });
                    
                    overlay.addEventListener('click', function() {
                        sidebar.classList.remove('open');
                        overlay.classList.remove('active');
                    });
                }
            });
        </script>
        
        <!-- CSRF Token Refresh Script -->
        <script>
            // CSRF Token Management
            function refreshCSRFToken() {
                fetch('/csrf-token', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.csrf_token) {
                        // Update meta tag
                        const metaTag = document.querySelector('meta[name="csrf-token"]');
                        if (metaTag) {
                            metaTag.setAttribute('content', data.csrf_token);
                        }
                        
                        // Update all CSRF input fields
                        const csrfInputs = document.querySelectorAll('input[name="_token"]');
                        csrfInputs.forEach(input => {
                            input.value = data.csrf_token;
                        });
                        
                        console.log('CSRF token refreshed');
                    }
                })
                .catch(error => {
                    console.error('Error refreshing CSRF token:', error);
                });
            }
            
            // Refresh CSRF token every 2 hours (less aggressive)
            setInterval(refreshCSRFToken, 2 * 60 * 60 * 1000);
            
            // Handle form submission errors
            document.addEventListener('DOMContentLoaded', function() {
                // Add error handling for all forms
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        // Check if CSRF token exists
                        const csrfInput = form.querySelector('input[name="_token"]');
                        if (!csrfInput || !csrfInput.value) {
                            console.warn('CSRF token missing, refreshing...');
                            refreshCSRFToken();
                        }
                    });
                });
            });
        </script>
    </body>
</html>
