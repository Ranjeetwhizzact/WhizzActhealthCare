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
                @if(auth()->user()->role === 'superadmin')
                <div class="flex justify-between  bg-white">
                    <h5 class="text-lg capitalize font-semibold">&nbsp;patients Details</h5>
                </div>
               @endif
               <div class="py-4">
                <div class="grid grid-cols-1">

                    <div class="w-full overflow-x-scroll  no-scrollbar">
                        <form method="GET" action="{{ route('medical-history') }}">
                            @csrf
                            <select name="status" onchange="this.form.submit()" class="w-48 border-2 py-1 px-2 rounded-sm">
                                <option value="">All</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>scheduled</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="rescheduled" {{ request('status') == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'canceled' : '' }}>Rejected</option>
                            </select>
                        </form>
                        <table class="text-xs w-full rounded-2xl mt-4">
                            <thead class="capitalize font-medium py-3 w-full text-gray-800 bg-emerald-100">
                                <th class="p-2 text-start ">Sr.no</th>
                                <th class="p-2 text-start capitalize border whitespace-nowrap">name</th>
                                <th class="p-2 text-start capitalize border whitespace-nowrap">Doctor </th>
                                <th class="p-2 text-start capitalize border whitespace-nowrap">Status</th>
                                <th class="p-2 text-start capitalize border whitespace-nowrap">Date</th>
                                <th class="p-2 text-start capitalize border whitespace-nowrap">Time</th>
                                <th class="p-2 text-start capitalize border whitespace-nowrap">Action</th>
                            </thead>
                            <tbody>
                                @if($patients->count())
                                @php
                                $cp = $patients->currentPage();
                                $perpage = $patients->perPage();
                                $startNumber = ($cp-1)*$perpage;
                                @endphp
                                @foreach($patients as $i => $patient)

                                <tr>
                                    <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $startNumber + $i + 1 }}</td>
                                    <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $patient->patient_first_name }}&nbsp; {{ $patient->patient_last_name }}</td>
                                    <td class="p-2 text-start capitalize border whitespace-nowrap">DR.{{ $patient->doctor_first_name }}&nbsp; {{ $patient->doctor_last_name }}</td>
                                    <td class="p-2 text-start capitalize border whitespace-nowrap  font-medium {{  $statuscolors[$patient->patient_status] ?? 'bg-black' }}">{{ ucfirst($patient->patient_status) }}</td>
                                    <td class="p-2 text-start capitalize border whitespace-nowrap">{{ \Carbon\Carbon::parse($patient->patient_dob)->format('d M Y') }}</td>
                                    <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $patient->schedule_time}}</td>
                                    <td class="p-2 text-start capitalize border whitespace-nowrap flex gap-3 mb-0">
                                        {{-- <a href="" id="modalToggle">View</a>, --}}
                                        @if ($patient->patient_status != 'canceled')
                                            <a href="{{ route('report', ['id' => $patient->hashed_id]) }}" class="bg-blue-300  px-3 py-1 rounded-full" title="Report"><i class="ri-file-chart-line text-xl"></i></a>
                                        @endif
                                        @if ($patient->patient_status != 'completed' && $patient->patient_status != 'canceled')
                                            <a href="{{ route('viewprescriptions', ['id' => $patient->hashed_id]) }}" class="bg-blue-300 px-3 py-1 rounded-full" title="Add Prescription"><i class="ri-add-line text-xl"></i></a>
                                        @endif
                                        @if (isset($patient->zoom_meeting_id) && $patient->zoom_meeting_id)
                                            <a href="{{ $patient->meeting }}" class="bg-blue-300  px-3  rounded-full text-md py-1" target="_blank" title="Join Meeting"><i class="ri-slideshow-3-line text-xl"></i></a>
                                        @endif
                                        @if ($patient->patient_status != 'completed' && $patient->patient_status != 'canceled')

                                        <form method="post" action="{{ route('changestatus') }}" class="mb-0">
                                            @csrf
                                            <input type="hidden" value="{{$patient->id}}" name="id">
                                            <select name="status" onchange="this.form.submit()" class="w-32 border-2 py-1 px-2 rounded-sm">
                                                <option value="" >Updated Status</option>
                                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="rescheduled" {{ request('status') == 're-scheduled' ? 'selected' : '' }}>Re-Scheduled</option>
                                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                            </select>
                                        </form>
                                        @endif
                                        {{-- <a href="">Edit</a> --}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                              </tbody>
                        </table>
                        <div class="flex justify-end">

                            <div class="mt-5 ">

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
    <div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden"></div>

    <!-- Modal Content -->
    <div id="modalContent" class="fixed inset-0 flex items-center justify-center p-4 hidden transform scale-75 opacity-0 modal-transition">
      <div class="relative w-full max-w-md px-3 py-4 bg-white rounded-xl shadow-xl divide-y">
        <h2 class="text-base font-semibold ">Profile</h2>
        <div >

            <div class="grid grid-cols-7 gap-3 p-3">
                <div class="col-span-7 md:col-span-1">

                    <div class="w-12 h-12 rounded-full ">
                        <img src="assests\img\avatar.png" alt="" srcset="" class="rounded-full w-full h-full">
                    </div>
                </div>
                <div  class="col-span-7 md:col-span-6">

                    <div class="flex justify-between ">
                        <div >
                            <h5 class="text-sm md:text-base font-semibold capitalize">Anjali Sharma</h5>
                                <div href="" class="text-white font-medium text-xs bg-blue-500  capitalize px-2 md:px-3 md:py-1 rounded-full flex items-center w-[100px] ">call link &nbsp;&nbsp;<i class="ri-file-copy-line text-sm"></i></div>
                        </div>
                        <div class="flex gap-2">
                            <a href="tel:" class="text-xl  text-gray-500"><i class="ri-phone-line"></i></a>
                            <a href="malto:" class="text-xl text-gray-500"><i class="ri-mail-line"></i></a>
                            <a href="" class="text-xl  text-gray-500"><i class="ri-edit-2-line"></i></a>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-5">
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Email</p>
                            <p class="font-semibold text-xs  md:text-sm">patients@gmail.com</p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Phone</p>
                            <p class="font-semibold  text-xs  md:text-sm">+91 77153460000</p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Gender</p>
                            <p class="font-semibold  text-xs  md:text-sm">Female</p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Marital Status</p>
                            <p class="font-semibold  text-xs  md:text-sm">Un-Married</p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">DOB</p>
                            <p class="font-semibold  text-xs  md:text-sm">12 Feb 2002</p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Blood Group</p>
                            <p class="font-semibold  text-xs  md:text-sm">B+</p>
                        </div>
                        <div class="col-span-2">
                            <p class="font-medium text-gray-400 text-xs">Address</p>
                            <p class="font-semibold  text-xs  md:text-sm">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sequi doloribus eaque adipisci repellat reiciendis veritatis amet fuga doloremque maiores mollitia voluptate, illum commodi, deleniti animi. </p>
                        </div>
                    </div>
                </div>
                <!-- <div class=""></div> -->
            </div>
            <div class="grid grid-cols-6 gap-4 h-18 items-center bg-gray-100 rounded p-4">
                <div class="col-span-1">

                    <div class="w-14 flex relative h-14">
                        <div class="w-14 h-14 rounded-full  me-0 absolute start-0 z-10"><img src="assests\img\doctor.png" alt="" srcset="" class="w-full h-full rounded-full"></div>

                    </div>
                </div>
                <div class="col-span-5">
                    <p class="text-base font-semibold">Dr. Aditya Yadav - Anjali .S</p>
                    <div class="flex gap-3">

                        <a href="#" class=" text-xs font-medium bg-gray-200 rounded-full px-3 py-1">Appoint Date : 01 Feb 2025 </a>
                        <a href="#" class="flex items-center text-xs text-white font-medium bg-[#0284C7] rounded-full px-3 py-1"><i class="ri-time-line"></i>&nbsp;3:45 AM</a>
                    </div>
                </div>
            </div>
        </div>

        <div class=" flex justify-end absolute top-0 end-0">
          <button id="closeModal" class="px-4 py-2  rounded text-lg"><i class="ri-close-fill"></i></button>
        </div>
      </div>
    </div>
</body>
@section('script')
@stop
@stop
</html>
