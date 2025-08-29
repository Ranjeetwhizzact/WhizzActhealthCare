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
            <div class="bg-white p-6 shadow-md ">
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-1 2xl:gap-7">
                    <div>

                        <div class="lg:w-full border-2 border-gray-100 rounded-md p-3 flex justify-between items-center ">
                            <div >
                                <p class="text-sm text-gray-500">Total Application</p>
                                <h5 class="text-2xl font-semibold">{{$totalCount}}</h5>
                            </div>
                            <div class="w-10 h-10 flex justify-center items-center rounded-full bg-red-200 text-red-600 text-lg">
                                <i class="ri-team-line"></i>
                            </div>
                        </div>
                    </div>
                    <div>

                        <div class=" lg:w-full border-2 border-gray-100 rounded-md p-3 flex justify-between items-center ">
                            <div >
                                <p class="text-sm text-gray-500">Today Application</p>
                                <h5 class="text-2xl font-semibold">{{$todayCount}}</h5>
                            </div>
                            <div class="w-10 h-10 flex justify-center items-center rounded-full bg-red-200 text-red-600 text-lg">
                                <i class="ri-group-line"></i>
                            </div>
                        </div>
                    </div>
                    <div>

                        <div class=" lg:w-full border-2 border-gray-100 rounded-md p-3 flex justify-between items-center ">
                            <div >
                                <p class="text-sm text-gray-500">Pending Consultations</p>
                                <h5 class="text-2xl font-semibold">{{$pendingAppointments}}</h5>
                            </div>
                            <div class="w-10 h-10 flex justify-center items-center rounded-full bg-yellow-200 text-yellow-600 text-lg">
                                <i class="ri-history-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div>

                        <div class="lg:w-full border-2 border-gray-100 rounded-md p-3 flex justify-between items-center ">
                            <div >
                                <p class="text-sm text-gray-500">Completed Consultations</p>
                                <h5 class="text-2xl font-semibold">{{$completedAppointments}}</h5>
                            </div>
                            <div class="w-10 h-10 flex justify-center items-center rounded-full bg-green-200 text-green-600 text-lg">
                                <i class="ri-shield-check-line"></i>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="grid grid-cols-3 gap-7 my-4">
                    <div class="col-span-3 lg:col-span-2">
                        <div class="flex justify-between">
                            <h5 class="text-lg font-semibold capitalize">Application Status Summary</h5>
                            <a href="{{url('medical-history')}}" class="underline underline-offset-3 text-blue-400 capitalize font-semibold text-nowrap">see All</a>
                        </div>
                        <div class="overflow-x-scroll  no-scrollbar">

                            <table class=" sm:w-full my-2  border-2 border-gray-100 rounded-2xl ">
                                <thead class="uppercase text-[13px] bg-emerald-100 text-gray-800 border-b-2 border-gray-100 rounded-2xl  ">
                                    <th class="p-3 text-start w-fit text-nowrap">name</th>
                                    <th class="p-3 text-start w-fit text-nowrap">Assigned Doctor</th>
                                    <th class="p-3 text-start w-fit text-nowrap">Date</th>
                                    <th class="p-3 text-start w-fit text-nowrap">Status</th>
                                    <th class="p-3 text-start w-fit text-nowrap">Action</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @if($appointments)
                                    @foreach ($appointments as $p)

                                    <tr>
                                        <td class="min-w-[180px]">
                                            <div class="flex items-center px-3  py-2 gap-3">
                                                {{-- <img src="{{url($p->profile_img)}}" alt="" srcset="" class="w-9 h-9 rounded-full"> --}}
                                                <div>
                                                    <p  class="text-[13px] font-semibold capitalize text-nowrap">{{$p->patient_first_name}} {{$p->patient_last_name}}</p>
                                                    <p  class="text-[13px] font-semibold text-gray-500 capitalize text-nowrap">{{$p->patient_phone}}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-sm text-gray-600 text-nowrap min-w-[180px]">Dr.{{$p->doctor_first_name}} {{$p->doctor_last_name}}</td>
                                        <td class="text-sm font-semibold text-nowrap min-w-[90px]">{{ \Carbon\Carbon::parse($p->appointment_date)->format('d-m-Y') }}</td>
                                        <td class="text-sm font-semibold capitalize text-nowrap "><p class="px-2 rounded-full  inline-block {{ $statusColors[$p->patient_status] ?? 'bg-black' }}">{{$p->patient_status}}</p>
                                        </td>
                                        <td class="flex gap-3 items-center mt-3 text-nowrap min-w-[80px] ">
                                            <a href="tel:{{$p->patient_phone}}" class="text-xl font-medium inline-block"><i class="ri-phone-line"></i></a>
                                            <a href="{{route('viewpatient', ['id' => $p->hashed_id])}}" class="text-xl font-medium inline-block"><i class="ri-eye-line"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>
                        <div id="chart" class="border-2 border-gray-100 rounded-lg mt-4"></div>
                    </div>
                    <div class="md:col-span-2 lg:col-span-1">

                        <div class="  ">
                            <div class="w-full p-4 border-2 border-gray-100 rounded-lg">
                                <div class="">

                                    <div class=" flex justify-between mb-3">
                                        <div class="font-semibold text-xl"> {{ \Carbon\Carbon::parse($selectedDate)->format('d M, Y') }} </div>
                                        <div class="">
                                            <a href="{{ route('dashboard', ['date' => \Carbon\Carbon::parse($selectedDate)->subDay()->format('Y-m-d')]) }}"
                                                class="btn btn-primary {{ $dayappointments->isEmpty() && $selectedDate <= \Carbon\Carbon::today()->format('Y-m-d') ? 'disabled' : '' }}">

                                                <i class="ri-arrow-left-s-line"></i>
                                            </a>
                                            <a href="{{ route('dashboard', ['date' => \Carbon\Carbon::parse($selectedDate)->addDay()->format('Y-m-d')]) }}"
                                                class="btn btn-primary {{ $dayappointments->isEmpty() ? 'disabled' : '' }}">

                                                <i class="ri-arrow-right-s-line"></i>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                                <ul class="">
                                    @if ($dayappointments->isEmpty())
                                    <p class="alert alert-warning">No appointments found for this date.</p>
                                    @else
                                    @foreach ($dayappointments as $a)
                                    <li class="flex justify-between py-1">
                                       {{-- <div class="col-span-2  ">
                                           <div class=" relative">

                                               <div class="w-9 inline-block h-9 rounded-full absolute start-0 z-10 border-2 border-gray-100">

                                                   <img src="{{url($a->profile_img)}}" alt="Dashboard Logo" class="w-full h-full object-contain rounded-full ">
                                               </div>
                                               <div class="w-9 inline-block h-9 rounded-full absolute end-0 border-2 border-gray-100 z-20 bg-gray-700">

                                                   <img src="{{url($a->doctor_profile)}}" alt="Dr." class="w-full h-full object-contain rounded-full text-center ">
                                               </div>
                                           </div>

                                       </div> --}}
                                       <div class="">
                                           <p class="text-base font-semibold">{{$a->patient_first_name}} - Dr.{{$a->doctor_first_name}}</p>
                                           <p class="text-xs text-gray-500">{{$a->patient_status}}</p>
                                       </div>
                                       <div class=" flex justify-self-end"><p class="text-xl text-gray-500 text-end"><i class="ri-arrow-right-up-line"></i></p></div>
                                    </li>
                                    @endforeach
                                    @endif

                             </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2">

                    </div>
                 </div>
            </div>
        </div>
    </div>
</body>
@section('script')
@stop
@stop
</html>
