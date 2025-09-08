@extends('layouts.app')
@section('title','Honest | Home ')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-green-100 to-green-50 rounded-3xl shadow-lg p-8 flex flex-col sm:flex-row items-center gap-8">
            <img src="{{ asset($doctor->image ?? '/doctors/profile/default.png') }}" 
                 alt="Doctor Profile" 
                 class="w-32 h-32 sm:w-40 sm:h-40 rounded-full object-cover shadow-md border-4 border-white">
            
            <div class="text-center sm:text-left flex-1">
                <h1 class="text-3xl font-bold text-gray-900">
                    Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                </h1>
                <p class="text-gray-700 text-lg mt-1">{{ $doctor->education }}</p>
                <p class="text-gray-500">{{ $doctor->year_of_experince }} Years Experience</p>
                <p class="mt-3 text-green-700 font-semibold bg-green-50 inline-block px-4 py-1 rounded-full">
                    ✅ Available for Online & Offline Consultation
                </p>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Info -->
            <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">👤 Personal Info</h2>
                <div class="space-y-2 text-gray-700">
                    <p><span class="font-medium">First Name:</span> {{ $doctor->first_name }}</p>
                    <p><span class="font-medium">Last Name:</span> {{ $doctor->last_name }}</p>
                    <p><span class="font-medium">Gender:</span> {{ $doctor->gender }}</p>
                    <p><span class="font-medium">Phone:</span> {{ $doctor->phone }}</p>
                    <p><span class="font-medium">Email:</span> {{ $doctor->email }}</p>
                </div>
            </div>

            <!-- Professional Info -->
            <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">🏥 Professional Info</h2>
                <div class="space-y-2 text-gray-700">
                    <p><span class="font-medium">Department:</span> {{ $doctor->department ?? 'N/A' }}</p>
                    <p><span class="font-medium">Associated Hospitals:</span> {{ $doctor->associated_hospitals }}</p>
                    <p><span class="font-medium">Education:</span> {{ $doctor->education }}</p>
                    <p><span class="font-medium">Experience:</span> {{ $doctor->year_of_experince }} Years</p>
                </div>
            </div>
        </div>

        <!-- Fees -->
        <div class="mt-6 bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">💰 Consultation Fees</h2>
            <p class="text-gray-700"><span class="font-medium">Online Fees:</span> ₹{{ $doctor->online_fees }}</p>
            <p class="text-gray-700"><span class="font-medium">Offline Fees:</span> ₹{{ $doctor->offline_fees }}</p>
        </div>

        <!-- Availability -->
        <div class="mt-6 bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">📅 Doctor Availability</h2>

            @php
                $availability = is_string($doctor->available_days) 
                    ? json_decode($doctor->available_days, true) 
                    : $doctor->available_days;

                // Order days manually (Mon → Sun)
                $orderedDays = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
            @endphp

            @if($availability && is_array($availability) && count($availability))
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($orderedDays as $day)
                        @if(isset($availability[$day]))
                            @php
                                $times = $availability[$day];
                                $start = $times['start'] ?? $times['start_time'] ?? null;
                                $end = $times['end'] ?? $times['end_time'] ?? null;
                            @endphp

                            <div class="bg-gradient-to-r from-green-50 to-green-100 p-5 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                                <h3 class="text-lg font-bold text-gray-800 capitalize mb-3">{{ ucfirst($day) }}</h3>
                                @if($start && $end)
                                    <p class="flex justify-between text-gray-700">
                                        <span>🕒 Start:</span> <span class="font-semibold text-green-700">{{ $start }}</span>
                                    </p>
                                    <p class="flex justify-between text-gray-700 mt-1">
                                        <span>⏰ End:</span> <span class="font-semibold text-red-600">{{ $end }}</span>
                                    </p>
                                @else
                                    <p class="text-gray-500 italic">No time set</p>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Availability not set</p>
            @endif
        </div>

        <!-- Signature -->
        <div class="mt-6 bg-white rounded-2xl shadow p-6 flex items-center gap-6 hover:shadow-lg transition">
            <h2 class="text-xl font-semibold text-gray-800">✍️ Doctor Signature:</h2>
            <img src="{{ asset($doctor->signature ?? '/doctors/signature/default.png') }}" 
                 alt="Doctor Signature" 
                 class="h-14 sm:h-20">
        </div>
    </div>
</div>
@endsection
