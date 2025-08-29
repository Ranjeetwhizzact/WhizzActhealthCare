@extends('layouts.app')
@section('title','Honest | Patients ')
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
                @if(auth()->user()->role === 'superadmin')
                <div class="flex justify-between  bg-white">
                    <h5 class="text-lg capitalize font-semibold">&nbsp;patients Details</h5><div class="">
                        <a href="{{ route('download.word') }}">
                            <button type="button" class="bg-blue-400 text-white mx-2 px-4 py-2 rounded-sm text-sm font-semibold ">Download Word</button></a>
                        <a href="{{url('createpatient')}}" class="d-inline-block p-2 rounded-md bg-emerald-400 text-white text-sm"><i class="ri-user-add-line "></i>&nbsp;&nbsp;Add patients</a>
                    </div>
                </div>
               @endif
               <div class="py-4">
                <div class="grid grid-cols-1">

                    <div class="w-full overflow-x-scroll  no-scrollbar">
                        <form method="GET" action="{{ route('patient') }}">
                            <select name="status" onchange="this.form.submit()" class="w-48 border-2 py-1 px-2 rounded-sm">
                                <option value="">All</option>
                                <option value="unassigned" {{ request('status') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>scheduled</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="rescheduled" {{ request('status') == 're-scheduled' ? 'selected' : '' }}>Re-Scheduled</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>canceled</option>
                            </select>
                        </form>
                        <table class="text-xs w-full rounded-2xl mt-4">
                            <thead class="capitalize font-medium py-3 w-full text-gray-800 bg-emerald-100">
                                <th class="p-2 text-start ">Sr.no</th>
                                <th class="p-2 text-start capitalize border whitespace-nowrap">name</th>
                                <th class="p-2 text-start capitalize border whitespace-nowrap">Email </th>
                                <th class="p-2 text-start capitalize border whitespace-nowrap">Phone</th>
                                <th class="p-2 text-start capitalize border whitespace-nowrap">Date of Birth</th>
                                <th class="p-2 text-start capitalize border whitespace-nowrap">Status</th>
                                <th class="p-2 text-start capitalize border whitespace-nowrap">Action</th>
                            </thead>
                            <tbody>

                                @if($patients->count())
                                @php
                                $cp = $patients->currentPage();
                                $perpage =  $patients->perPage();
                                $startNumber = ($cp - 1) * $perpage;
                                @endphp


                                @foreach ($patients as $i => $p )

                                <tr  >
                                    <td class="p-2 text-start capitalize border whitespace-nowrap">     {{ $startNumber + $i + 1 }}</td>
                                    <td class="p-2 text-start capitalize border whitespace-nowrap">{{$p->first_name}}&nbsp;{{$p->last_name}}</td>
                                    {{-- <td class="p-2 text-start capitalize border whitespace-nowrap">DR.Adity</td> --}}
                                    <td class="p-2 text-start border whitespace-nowrap  font-medium">{{$p->email}}</td>
                                    <td class="p-2 text-start capitalize border whitespace-nowrap">{{$p->phone}}</td>
                                    <td class="p-2 text-start capitalize border whitespace-nowrap">{{ \Carbon\Carbon::parse($p->dob)->format('d M Y') }} </td>
                                    {{-- <td class="p-2 text-start capitalize border whitespace-nowrap">{{$p->address}}</td> --}}
                                    <td class="p-2 text-start capitalize border whitespace-nowrap  ">
                                        <span class="px-2 py-1 rounded-full {{ $statusColors[$p->status] ?? '' }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td class="p-2 text-start capitalize border whitespace-nowrap flex items-center gap-2">
                                        <div>

                                            <a href="{{ route('schedule', ['id' => $p->hashed_id]) }}"><button type="button" class="view-btn  px-3 py-1 rounded-full bg-green-500 text-white hover:bg-green-400" title="Book Appointment"><i class="ri-calendar-event-line"></i></button></a>

                                            <a href="{{ route('viewpatient', ['id' => $p->hashed_id]) }}" class="bg-yellow-500 text-white rounded-full px-3 py-1 inline-block bg-green-500 text-white hover:bg-green-400"><i class="ri-eye-line"></i></a>
                                        </div>

                                        <div >

                                            <a href="{{ route('editpatient', ['id' => $p->hashed_id]) }}" class="bg-yellow-500 text-white rounded-full px-3 py-1 inline-block hover:bg-yellow-400"><i class="ri-edit-line"></i></a>
                                        </div>
                                        <form action="{{ route('deletepatient', $p->hashed_id) }}" method="POST" class="mb-0" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class=" bg-red-500 text-white text-xs px-3 py-1 rounded-full mb-0"><i class="ri-delete-bin-6-line"></i></button>
                                        </form>


                                    </td>
                                </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                        <div class="flex justify-end">

                            <div class="mt-5">
                                <div class="flex justify-between w-[260px] items-center mt-4">
                                    @if($patients->onFirstPage())
                                        <span class="text-gray-500 px-4 py-1 text-sm bg-gray-200 rounded">Previous</span>
                                    @else
                                        <a href="{{ $patients->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}"
                                           class="px-4 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
                                            Previous
                                        </a>
                                    @endif

                                    <span class="text-gray-700">
                                        Page {{ $patients->currentPage() }} of {{ $patients->lastPage() }}
                                    </span>

                                    @if ($patients->hasMorePages())
                                        <a href="{{ $patients->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}"
                                           class="px-4 py-1 bg-red-500 text-sm text-white rounded hover:bg-red-600">
                                            Next
                                        </a>
                                    @else
                                        <span class="text-gray-500 px-4 py-1 bg-gray-200 rounded text-sm">Next</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

               </div>
            </div>
        </div>
        <!-- Right Side: Product Details -->
        {{-- <div class="bg-white p-4 rounded shadow border" id="productDetail">
            <h2 class="text-xl font-bold mb-4">Product Details</h2>
            <p><strong>ID:</strong> <span id="detailId">-</span></p>
            <p><strong>Name:</strong> <span id="detailName">-</span></p>
            <p><strong>Description:</strong> <span id="detailDescription">-</span></p>
            <p><strong>Price:</strong> <span id="detailPrice">-</span></p>
        </div> --}}
    </div>
    <div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>

    <!-- Modal Content -->
   <!-- Modal Container -->
<div id="modalContent" class="fixed inset-0 flex items-center justify-center p-4 hidden transform scale-75 opacity-0 modal-transition">
    <div class="relative w-full max-w-md px-3 py-4 bg-white rounded-xl shadow-xl divide-y">

        <!-- Modal Header with Close Button -->
        <div class="flex justify-between items-center">
            <h2 class="text-base font-semibold">Profile</h2>
            <button id="closeModal" class="text-lg p-2 rounded hover:bg-gray-200" aria-label="Close modal">
                <i class="ri-close-fill"></i>
            </button>
        </div>

        <!-- Profile Details Section -->
        <div id="productDetail" class="p-3">
            <div class="grid grid-cols-7 gap-3">

                <!-- Profile Image -->
                <div class="col-span-7 ">
                    <div class="w-12 h-12 rounded-full">
                        <img src="" id="profile_img" alt="Profile Image" class="rounded-full w-full h-full">
                    </div>
                </div>

                <!-- User Info -->
                <div class="col-span-7 ">
                    <div class="grid grid-cols-2">
                        <div>
                            <h5 class="text-sm md:text-base font-semibold capitalize" id="name"></h5>
                            <div class="text-white font-medium text-xs bg-blue-500 capitalize px-2 md:px-3 md:py-1 rounded-full flex items-center w-[100px] cursor-pointer">
                                Call Link &nbsp;&nbsp;<i class="ri-file-copy-line text-sm"></i>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="tel:" class="text-xl text-gray-500 phone"><i class="ri-phone-line"></i></a>
                            <a href="#" class="text-xl text-gray-500" id="email"><i class="ri-mail-line"></i></a>
                            <a href="#" class="text-xl text-gray-500"><i class="ri-edit-2-line"></i></a>
                        </div>
                    </div>

                    <!-- User Details -->
                    <div class="grid grid-cols-2 gap-3 mt-5">
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Email</p>
                            <p class="font-semibold text-xs md:text-sm email"></p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Phone</p>
                            <p class="font-semibold text-xs md:text-sm" id="phone"></p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Gender</p>
                            <p class="font-semibold text-xs md:text-sm" id="gender"></p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Marital Status</p>
                            <p class="font-semibold text-xs md:text-sm" id="marital_status"></p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">DOB</p>
                            <p class="font-semibold text-xs md:text-sm" id="dob"></p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Blood Group</p>
                            <p class="font-semibold text-xs md:text-sm" id="blood_group"></p>
                        </div>
                        <div class="col-span-2">
                            <p class="font-medium text-gray-400 text-xs">Address</p>
                            <p class="font-semibold text-xs md:text-sm" id="address"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</body>
@section('script')
<script>


</script>
@stop
@stop
</html>
