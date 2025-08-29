
@extends('layouts.app')
@section('title','Honest | Home ')
@section('content')
    <div class="flex h-screen divide-x-2 divide-gray-100 ">
        <!-- Sidebar -->
        @include('common.sidenav')
       

        <!-- Main Content -->
        <div class="main-content flex-1 ml-64 transition-all duration-300">
            <!-- Welcome Section -->
           @include('common.header')
    <div class="flex items-center justify-center h-screen bg-gray-100">

        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center">Register</h2>
            @if ($errors->any())
                <div class="text-red-500">{{ $errors->first() }}</div>
            @endif
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <input type="hidden" name="id" value="{{isset($user->id)?$user->id:''}}" class="w-full px-4 py-2 border rounded-lg" required>
                    <label class="block">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                @if(request()->is('register'))
                <div class="mb-4">
                    <label class="block">Password</lvabel>
                    <input type="password" name="password" value="" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block">Confirm Password</label>
                    <input type="password" name="password_confirmation" value="" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                @else
                <div class="mb-4">
                    <label class="block">Password</lvabel>
                    <input type="password" name="password" value="" class="w-full px-4 py-2 border rounded-lg" >
                </div>
                <div class="mb-4">
                    <label class="block">Confirm Password</label>
                    <input type="password" name="password_confirmation" value="" class="w-full px-4 py-2 border rounded-lg" >
                </div>
                @endif

                <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Roles</label>
                <select class="w-full border border-gray-300 p-2 rounded-lg mb-2" name="role">
                    <option value="superadmin"  {{ (old('role', $user->role ?? '') == 'superadmin') ? 'selected' : '' }}  >Superadmin</option>
                    <option value="admin" {{ (old('role', $user->role ?? '') == 'admin') ? 'selected' : '' }} >Admin</option>
                    <option value="user"{{ (old('role', $user->role ?? '') == 'user') ? 'selected' : '' }} >User</option>
                </select>
                <button type="submit" class="w-full py-2 bg-[#DC2626] text-white rounded-lg">{{ isset($user) ? 'Update' : 'Register' }}</button>
            </form>
            <p class="mt-4 text-center">
                Already have an account? <a href="{{ route('login') }}" class="text-blue-600">Login</a>
            </p>
        </div>
    </div>
</body>
@section('script')
@stop
@stop
</html>
