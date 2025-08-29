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

            <!-- Table Section -->
            <div class="p-5 bg-white">
            
                <form action="{{ isset($doctor) ? route('update.doctor', $doctor->id) : route('store.doctor') }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
                    @csrf
                    <div class="grid md:grid-cols-4 lg:grid-cols-5 gap-4 divide-x">
                        <div class="col-span-4 md:col-span-2 lg:col-span-3 p-3">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="block text-gray-700 font-medium mb-2 text-sm">Profile Image</label>
                                    <label for="imag">
                                        <img id="imagePreview"  
                                            src="{{ isset($doctor->image) ? asset($doctor->image) : asset('assests/img/avatar.png') }}" 
                                            alt="Profile Image" 
                                            class="w-32 h-32 object-cover mb-2 rounded">
                                    </label>
                                    <input type="file" name="image" accept="image/*" id="imag"
                                        class="w-full border hidden border-gray-300 p-2 rounded-lg"
                                        onchange="previewImage(event, 'imagePreview')">
                                    @error('image')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Signature -->
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="block text-gray-700 font-medium mb-2 text-sm">Signature</label>
                                    <label for="signature">
                                        <img id="signaturePreview" 
                                           src="{{ isset($doctor->signature) && $doctor->signature ? asset($doctor->signature) : asset('assests/img/sine.png') }}" 
                                            alt="Signature" 
                                            class="w-32 h-16 object-contain mb-2 border rounded">
                                    </label>
                                    <input type="file" name="signature" accept="image/*" id="signature"
                                        class="w-full hidden border border-gray-300 p-2 rounded-lg"
                                        onchange="previewImage(event, 'signaturePreview')">
                                    @error('signature')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- First Name -->
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="block text-gray-700 font-medium mb-2 text-sm">First Name</label>
                                    <input type="text" name="first_name" value="{{ old('first_name', $doctor->first_name ?? '') }}" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="John" >
                                    @error('first_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="block text-gray-700 font-medium mb-2 text-sm">Last Name</label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $doctor->last_name ?? '') }}" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="Doe" >
                                    @error('last_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Personal Email -->
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="block text-gray-700 font-medium mb-2 text-sm">Personal Email ID</label>
                                    <input type="email" name="email" value="{{ old('email', $doctor->email ?? '') }}" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="example@email.com" >
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Company Email -->
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="block text-gray-700 font-medium mb-2 text-sm">Company Email ID</label>
                                    <input type="email" name="username" value="{{ old('username', $user->email ?? '') }}" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="example@email.com" >
                                    @error('username')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="block text-gray-700 font-medium mb-2 text-sm">Password</label>
                                    <input type="password" name="password" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="Password" required>
                                    @error('password')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="block text-gray-700 font-medium mb-2 text-sm">Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone', $doctor->phone ?? '') }}" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="+123456789">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                     <div class="col-span-2 lg:col-span-1">
                                    <label class="block text-gray-700 font-medium mb-2 text-sm">Gender</label>
                                    <select class="w-full border border-gray-300 p-2 rounded-lg" name="gender">
                                        <option value="male" {{ isset($doctor->gender) && $doctor->gender == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ isset($doctor->gender) && $doctor->gender == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ isset($doctor->gender) && $doctor->gender == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                               <div class="col-span-2 lg:col-span-1">
                                    <label class="block text-gray-700 font-medium mb-2 text-sm">Education</label>
                                    <input type="text" name="education" value="{{ old('education', $doctor->education ?? '') }}" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="" >
                                    @error('education')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-span-2 lg:col-span-1">
                                    <label class="block text-gray-700 font-medium mb-2 text-sm">Reference Number</label>
                                    <input type="text" name="reference_number" value="{{isset($doctor->reference_number)?$doctor->reference_number:''}}" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="Reference Number" >
                                </div>
                               <div class="col-span-2 lg:col-span-1">
                                 <label class="block text-gray-700 font-medium mb-2 text-sm">Degree</label>
                                    <input type="file" name="degree" class="w-full border border-gray-300 p-2 rounded-lg">
                                @error('degree')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg w-full hover:bg-gray-500">Submit</button>
                            </div>
                        </div>
                        <div class="col-span-4 md:col-span-2 lg:col-span-2 divide-y">
                            <h5 class="px-4 pt-1 pb-2">Availability</h5>
                            <div id="schedule-form">
                                <div id="schedule-fields" class="space-y-4">
                                    @php $weekdays = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday']; @endphp
                                    @foreach ($weekdays as $day)
                                        @php
                                            $isAvailable = isset($doctor->available_days[$day]) && !empty($doctor->available_days[$day]['start_time']) && !empty($doctor->available_days[$day]['end_time']);
                                        @endphp
                                        @if ($isAvailable)
                                            <div class="flex items-center justify-between p-2 rounded-md" id="{{ $day }}">
                                                <span class="text-gray-700 font-medium w-20 capitalize">{{ ucfirst($day) }}</span>
                                                <input type="time" name="available_days[{{ $day }}][start_time]" class="p-2 border rounded-md w-24" value="{{ $doctor->available_days[$day]['start_time'] }}">
                                                <input type="time" name="available_days[{{ $day }}][end_time]" class="p-2 border rounded-md w-24" value="{{ $doctor->available_days[$day]['end_time'] }}">
                                                <button type="button" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="removeDay('{{ $day }}')">X</button>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <button type="button" id="add-day" class="mt-4 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Add Day</button>
                                <button type="button" class="mt-2 w-full bg-gray-600 text-white py-2 rounded hover:bg-gray-700" onclick="resetSchedule()">Reset All</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(function () {
        $("#users-search").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "/searchuser",
                    data: { term: request.term },
                    dataType: "json",
                    success: function (data) {
                        response($.map(data, function (useremail) {
                            return {
                                label: useremail.email,
                                value: useremail.email,
                                id: useremail.id,
                                data: useremail
                            };
                        }));
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching users:", error);
                    }
                });
            },
            minLength: 1,
            select: function (event, ui) {
                $("#user-id").val(ui.item.id);
            }
        });
    });
</script>
<script>
function previewImage(event, previewId) {
    const input = event.target;
    const preview = document.getElementById(previewId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
