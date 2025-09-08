{{-- resources/views/errors/custom.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center h-screen">

    <div class="bg-white shadow-xl rounded-3xl p-10 max-w-lg w-full text-center transform transition-all hover:scale-105 duration-300">
        
        {{-- Icon --}}
        <div class="flex justify-center mb-6">
            <div class="bg-red-100 text-red-600 p-4 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-10 w-10" fill="none" 
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                </svg>
            </div>
        </div>

        {{-- Title --}}
        <h1 class="text-2xl font-extrabold text-gray-800 mb-3">Oops! Something went wrong</h1>

        {{-- Dynamic error message --}}
        <p class="text-gray-600 text-base leading-relaxed mb-6">
            {{ $message ?? 'An unexpected error occurred. Please try again.' }}
        </p>

        {{-- Buttons --}}
        <div class="flex justify-center space-x-4">
            <a href="{{ url()->previous() }}" 
               class="px-5 py-2 rounded-xl bg-blue-600 text-white font-medium shadow hover:bg-blue-700 transition">
               Go Back
            </a>
            {{-- <a href="{{ url('/') }}" 
               class="px-5 py-2 rounded-xl bg-gray-200 text-gray-800 font-medium shadow hover:bg-gray-300 transition">
            Home
            </a> --}}
        </div>
    </div>

</body>
</html>
