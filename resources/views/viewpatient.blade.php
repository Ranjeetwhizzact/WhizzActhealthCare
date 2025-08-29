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
            <div class="grid divide-x">
                @if($patient)
                {{-- @foreach ($patient as $patient) --}}
                    
                <div class="md:col-span-2 p-4">
                    <div class="rounded-lg">
                        <div class="flex gap-4 h-14 items-center">
                            <div>
                                <p class="text-base md:text-lg font-semibold capitalize mb-1">
                                    {{$patient->first_name}} {{$patient->last_name}}
                                </p>
                            </div>
                        </div>
                    </div>
                
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-5 ">
                        <div class="col-span-1">
                            <p class="font-medium text-gray-400 text-sm">Email</p>
                            <p class="font-semibold text-md">{{$patient->email}}</p>
                        </div>
                        <div class="col-span-1">
                            <p class="font-medium text-gray-400 text-sm">Phone</p>
                            <p class="font-semibold text-md">+91 {{$patient->phone}}</p>
                        </div>
                        <div class="col-span-1">
                            <p class="font-medium text-gray-400 text-sm">Gender</p>
                            <p class="font-semibold text-md">{{$patient->gender}}</p>
                        </div>
                        <div class="col-span-1">
                            <p class="font-medium text-gray-400 text-sm">Marital Status</p>
                            <p class="font-semibold text-md">{{$patient->marital_status}}</p>
                        </div>
                        <div class="col-span-1">
                            <p class="font-medium text-gray-400 text-sm">DOB</p>
                            <p class="font-semibold text-md">{{$patient->dob}}</p>
                        </div>
                        <div class="col-span-1">
                            <p class="font-medium text-gray-400 text-sm">Proposal Number</p>
                            <p class="font-semibold text-md">{{$patient->proposal_number}}</p>
                        </div>
                        <div class="col-span-1">
                            <p class="font-medium text-gray-400 text-sm">Status</p>
                            <p class="font-semibold text-md">{{$patient->status}}</p>
                        </div>
                        <div class="col-span-1">
                            <p class="font-medium text-gray-400 text-sm">View documents</p>
                            <p class=" text-sm mt-3"><a href="{{$patient->id_document}}" target="_blank" class="rounded-full px-3 py-2 bg-green-400 hover:bg-green-500 text-white"> 
                                {{$patient->id_type}}
                            </a></p>
                        </div>
                        <div class="col-span-1 sm:col-span-2">
                            <p class="font-medium text-gray-400 text-sm">Address</p>
                            <p class="font-semibold text-md capitailze">{{$patient->address}}</p>
                        </div>
                
                       
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-5 ">
                    <div class="col-span-full font-semibold text-md my-3">
                        Insurance Company Details
                    </div>
            
                    <div class="col-span-1">
                        <p class="font-medium text-gray-400 text-sm">Third Party Administrator</p>
                        <p class="font-semibold text-md">{{$patient->third_party_administrator}}</p>
                    </div>
                    <div class="col-span-1">
                        <p class="font-medium text-gray-400 text-sm">Insurance Company Name</p>
                        <p class="font-semibold text-md">{{$patient->insurance_company_name}}</p>
                    </div>
                    <div class="col-span-1">
                        <p class="font-medium text-gray-400 text-sm">Insurance Company Email</p>
                        <p class="font-semibold text-md">{{$patient->insurance_company_email}}</p>
                    </div>
                    <div class="col-span-1">
                        <p class="font-medium text-gray-400 text-sm">View Forms</p>
                     <p class="text-sm mt-3">
    @if ($patient->documents != null)
        <a href="{{ $patient->documents }}" target="_blank" class="rounded-full px-3 py-2 bg-green-400 hover:bg-green-500 text-white">
            Insurance Forms
        </a>
    @else
        Not uploaded
    @endif
</p>
                    </div>
                </div>
                </div>
                {{-- @endforeach --}}
                @endif
           
              
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
