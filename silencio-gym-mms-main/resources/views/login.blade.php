<x-layout>
    <div class="flex items-center justify-center min-h-screen w-full p-2 sm:p-4 relative">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img class="w-full h-full object-cover opacity-10" src="{{ asset('images/gym-image.png') }}" alt="Gym Background">
        </div>
        
        <div class="flex flex-col lg:flex-row items-center justify-center w-full max-w-sm sm:max-w-md lg:max-w-4xl rounded-lg shadow-lg overflow-hidden bg-white relative z-10">
            <div class="flex flex-col flex-1 justify-center items-center gap-3 sm:gap-4 lg:gap-5 p-4 sm:p-6 lg:p-8 w-full">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold text-center text-gray-900">Silencio System</h1>
                <h2 class="text-xs sm:text-sm lg:text-base text-gray-600 text-center">Log in to continue</h2>
                
                @if ($errors->any())
                    <div class="w-full max-w-xs sm:max-w-sm bg-red-50 border border-red-200 text-red-700 px-2 sm:px-3 lg:px-4 py-2 sm:py-3 rounded-lg">
                        <ul class="list-disc list-inside text-xs sm:text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="w-full max-w-xs sm:max-w-sm bg-green-50 border border-green-200 text-green-700 px-2 sm:px-3 lg:px-4 py-2 sm:py-3 rounded-lg">
                        <p class="text-xs sm:text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="w-full max-w-xs sm:max-w-sm bg-red-50 border border-red-200 text-red-700 px-2 sm:px-3 lg:px-4 py-2 sm:py-3 rounded-lg">
                        <p class="text-xs sm:text-sm">{{ session('error') }}</p>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login.post') }}" class="flex flex-col w-full max-w-xs sm:max-w-sm mx-auto" id="loginForm">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="mb-3 sm:mb-4 lg:mb-5">
                        <label for="email" class="block mb-1 sm:mb-2 text-xs sm:text-sm font-medium text-gray-900">Your email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="bg-white border-2 border-gray-300 text-gray-900 text-xs sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 sm:p-3 lg:p-2.5 min-h-[40px] sm:min-h-[44px] shadow-sm" placeholder="name@example.com" required />
                    </div>
                    <div class="mb-3 sm:mb-4 lg:mb-5">
                        <label for="password" class="block mb-1 sm:mb-2 text-xs sm:text-sm font-medium text-gray-900">Your password</label>
                        <input type="password" id="password" name="password" class="bg-white border-2 border-gray-300 text-gray-900 text-xs sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 sm:p-3 lg:p-2.5 min-h-[40px] sm:min-h-[44px] shadow-sm" required />
                    </div>
                    <div class="flex flex-col gap-2 sm:gap-3 mb-3 sm:mb-4 lg:mb-5">
                        <a href="{{ route('member.register') }}" class="text-xs sm:text-sm text-blue-600 hover:underline text-center">Sign up</a>
                        <div class="flex items-center justify-center">
                            <a href="#" class="text-xs sm:text-sm text-blue-600 hover:underline">Forgot password?</a>
                        </div>
                    </div>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs sm:text-sm w-full px-4 sm:px-5 py-2 sm:py-3 lg:py-2.5 text-center min-h-[40px] sm:min-h-[44px]">Login</button>
                </form>
                
                <!-- CSRF Token Refresh Script -->
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('loginForm');
                    const csrfInput = form.querySelector('input[name="_token"]');
                    
                    // Refresh CSRF token every 30 minutes
                    setInterval(function() {
                        fetch('/csrf-token')
                            .then(response => response.json())
                            .then(data => {
                                if (data.csrf_token) {
                                    csrfInput.value = data.csrf_token;
                                    // Update meta tag if it exists
                                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                                    if (metaTag) {
                                        metaTag.setAttribute('content', data.csrf_token);
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error refreshing CSRF token:', error);
                            });
                    }, 30 * 60 * 1000); // 30 minutes
                    
                    // Handle form submission
                    form.addEventListener('submit', function(e) {
                        // Ensure CSRF token is present
                        if (!csrfInput.value) {
                            e.preventDefault();
                            alert('Session expired. Please refresh the page and try again.');
                            window.location.reload();
                        }
                    });
                });
                </script>
            </div>
            <div class="hidden lg:flex lg:flex-2">
                <img class="object-cover w-full h-full rounded-r-lg" src="{{ asset('images/gym-image.png') }}" alt="Gym Image">
            </div>
        </div>
    </div>
</x-layout>