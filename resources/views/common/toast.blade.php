@props(['type' => session('success') ? 'success' : (session('error') ? 'error' : ''), 'message' => session('success') ?? session('error')])

@if($message)
    <div id="toast-container" class="fixed top-5 right-5 z-50">
        <div id="toast-message" 
            class="flex items-center w-80 px-4 py-3 rounded-lg shadow-lg transition-opacity duration-300 opacity-100
                   {{ $type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">

            <!-- Icon -->
            <div class="mr-3">
                @if($type === 'success')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"></path>
                    </svg>
                @else
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                @endif
            </div>

            <!-- Message -->
            <span id="toast-text" class="flex-1">
                {{ $message }}
            </span>

            <!-- Close Button -->
            <button id="close-toast" class="ml-4 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Auto-Dismiss Script -->
    <script>
     
    </script>
@endif