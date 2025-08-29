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

              
               
                <form>
            <div class="grid md:grid-cols-4 lg:grid-cols-5 gap-4 divide-x">
                @if($report)
                @foreach ($report as $report)
                    
                <div class="md:col-span-2  ">
                    <h5 class="px-4 pt-1 pb-2 text-xl font-semibold">Report</h5>
                    <div class="bg-gray-100 rounded-lg p-4">
                        <div class="flex gap-4 h-14 items-center">
                            {{-- <div class="w-20 flex relative h-14">
                                <div class="w-14 h-14 rounded-full  me-0 absolute start-0 z-10"><img src="{{url($report->doctor_profile)}}" alt="" srcset="" class="w-full h-full rounded-full"></div>
                                <div class="w-14 h-14 rounded-full  ms-0 absolute end-0 z-20"><img src="{{url($report->profile_img)}}" alt="" srcset="" class="w-full h-full rounded-full"></div>
                            </div> --}}
                            <div class="">
                                <p class="text-base font-semibold capitalize mb-1">Dr.{{$report->physician_name}} - {{$report->patient_name}} </p>
                                <a href="#" class="text-white text-xs font-medium bg-[#0284C7] rounded-full px-3 py-1 ">{{ \Carbon\Carbon::parse($report->appointment_date)->format('d M Y') }} </a>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-5">
                        <div class="my-1 col-span-2">
                            <p class="font-medium text-gray-400 text-xs">Email</p>
                            <p class="font-semibold  text-sm">{{$report->patient_email}}</p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Phone</p>
                            <p class="font-semibold  text-sm">+91 {{$report->patient_phone}}</p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Gender</p>
                            <p class="font-semibold  text-sm">{{$report->patient_gender}}</p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">Patient Age</p>
                            <p class="font-semibold  text-sm">{{$report->patient_age}}</p>
                        </div>
                        <div class="my-1">
                            <p class="font-medium text-gray-400 text-xs">DOB</p>
                            <p class="font-semibold  text-sm">{{$report->patient_dob}}</p>
                        </div>
            
                        <div class="col-span-2">
                            <p class="font-medium text-gray-400 text-xs">Address</p>
                            <p class="font-semibold  text-sm">{{$report->patient_address}}</p>
                        </div>
                    </div>
                
                </div>
                @endforeach
                @endif
                <div class="md:col-span-2 lg:col-span-3 p-3">
                    <div class="flex justify-between  bg-white">
                    <h5 class="text-base capitalize font-semibold">&nbsp;Doctor Notes</h5> <a href="{{ url('/prescription/'.$report->prescription_id.'/pdf') }}" class="d-inline-block p-2 rounded-md bg-gray-200 text-sm"><i class="ri-download-2-line mt-2"></i>&nbsp;&nbsp;Download note</a>
                </div>
                <p class="p-2 text-sm">{{$report->notable_condition}}</p>
                <div>
                    <h3 class="text-lg font-semibold mb-2">Allergies</h3>
                    <p class=" text-sm">
                        {{$report->allergies}}
                    </p>
                </div>
                <div class="mt-4">
    <h3 class="text-lg font-semibold mb-2">Medications</h3>

    @if(!empty($report->medications))
        @php
            $medications = json_decode($report->medications, true);
        @endphp

        @if(!empty($medications))
            <div class="overflow-x-auto">
                <table class="table-auto border-collapse border border-gray-400 w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="border border-gray-400 px-2 py-1">#</th>
                            <th class="border border-gray-400 px-2 py-1">Medication Name</th>
                            <th class="border border-gray-400 px-2 py-1">Purpose</th>
                            <th class="border border-gray-400 px-2 py-1">Dosage</th>
                            <th class="border border-gray-400 px-2 py-1">Route</th>
                            <th class="border border-gray-400 px-2 py-1">Frequency</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medications as $index => $med)
                            <tr>
                                <td class="border border-gray-400 px-2 py-1">{{ $index + 1 }}</td>
                                <td class="border border-gray-400 px-2 py-1">{{ $med['medication_name'] }}</td>
                                <td class="border border-gray-400 px-2 py-1">{{ $med['purpose'] }}</td>
                                <td class="border border-gray-400 px-2 py-1">{{ $med['dosage'] }}</td>
                                <td class="border border-gray-400 px-2 py-1">{{ $med['route'] }}</td>
                                <td class="border border-gray-400 px-2 py-1">{{ $med['frequency'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No medications found.</p>
        @endif
    @else
        <p class="text-gray-500">No medications recorded.</p>
    @endif
</div>

                
                    </div>
                   

                </form>
            </div>
            </div>
        </div>
    </div>
   
</body>
@section('script')
@stop
@stop
</html>
