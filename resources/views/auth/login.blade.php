<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-lg">
        <h2 class="text-3xl font-semibold text-center text-gray-800">Welcome Back</h2>
        <p class="text-sm text-gray-500 text-center mb-6">Please log in to continue</p>

        @if ($errors->any())
            <div class="mb-4 text-sm text-center text-red-600 bg-red-100 p-3 rounded-md">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block font-medium text-gray-700">Email</label>
                <input type="email" name="email" class="w-full px-4 py-3 mt-1 text-gray-800 border rounded-lg shadow-sm focus:ring-1 focus:ring-red-400 focus:border-red-400 outline-none transition" placeholder="Enter your email" required>
            </div>
            <div>
                <label class="block font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 mt-1 text-gray-800 border rounded-lg shadow-sm focus:ring-1 focus:ring-red-400 focus:border-red-400 outline-none transition" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="w-full py-3 text-lg font-semibold text-white transition bg-red-600 rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-300">
                Login
            </button>
        </form>

        <div class="mt-5 text-center">
            <a href="#" class="text-sm text-gray-500 hover:text-red-600">Forgot your password?</a>
        </div>

        {{-- <p class="mt-5 text-center text-gray-600">
            Don't have an account? <a href="{{ route('register') }}" class="text-red-600 font-medium hover:underline">Register</a>
        </p> --}}
    </div>

</body>
</html>
