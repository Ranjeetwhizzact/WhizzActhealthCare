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
                    <h5 class="text-lg capitalize font-semibold ">&nbsp;Doctors</h5> <a href="{{url('createdoctor')}}" class="d-inline-block p-2 rounded-md bg-emerald-400 text-sm text-white"><i class="ri-user-add-line "></i>&nbsp;&nbsp;Add Doctors</a>
                </div>
                @endif
     
              <table class="text-xs w-full rounded-2xl mt-4">
                <thead class="capitalize font-medium py-3 w-full text-gray-800 bg-emerald-100">
                    <th class="p-2 text-start ">Sr.no</th>
                    <th class="p-2 text-start capitalize border whitespace-nowrap">name</th>
                    <th class="p-2 text-start capitalize border whitespace-nowrap">Education</th>

                    <th class="p-2 text-start capitalize border whitespace-nowrap">Email </th>
                    <th class="p-2 text-start capitalize border whitespace-nowrap">Available</th>
                    {{-- <th class="p-2 text-start capitalize border whitespace-nowrap">Date</th>
                    <th class="p-2 text-start capitalize border whitespace-nowrap">Time</th> --}}
                    <th class="p-2 text-start capitalize border whitespace-nowrap">Action</th>
                </thead>
                <tbody>
                    @if($doctors->count())
                @php
                    $cp = $doctors->currentPage();
                    $perpage = $doctors->perPage();
                    $startNumber = ($cp - 1) * $perpage;
                @endphp
                     @foreach ($doctors as $i => $d)     
                    <tr>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $startNumber + $i + 1 }}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">Dr.{{ $d->first_name }} {{ $d->last_name }}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $d->education }}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap  font-medium ">{{$d->email}}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap text-yellow-500 font-medium ">
                            @php
                            $weekdays = [
                                'sunday' => 'S', 'monday' => 'M', 'tuesday' => 'T',
                                'wednesday' => 'W', 'thursday' => 'T', 'friday' => 'F', 'saturday' => 'S'
                            ];
                        @endphp
        
                        <ul class="uppercase flex justify-between  ">
                            @foreach ($weekdays as $day => $letter)
                                @php
                                    $isAvailable = isset($d->available_days[$day]); // Check if exists
                                @endphp
                                <li class="{{ $isAvailable ? 'text-green-500 font-bold' : 'text-gray-300' }}">
                                    {{ $letter }}
                                </li>
                            @endforeach
                        </ul> 
                        </td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap flex items-center  gap-3">       
                            <div>
                                <a href="{{ route('editdoctor', $d->hashed_id) }}" class="bg-yellow-500 text-white rounded-full px-3 py-1 inline-block hover:bg-yellow-400"><i class="ri-edit-line"></i>
    
                        </a>
                                </div>   
                                <div>

                                    <form action="{{ route('deletedoctor', $d->hashed_id) }}" method="POST" class="w-7 h-6  text-white flex px-3 items-center bg-red-500 rounded-full justify-center mb-0  " onsubmit="return confirm('Are you sure you want to delete this Doctor');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="  text-white text-xs  py-1 mb-0"><i class="ri-delete-bin-6-line"></i></button>
                                    </form>
                                    </div>  
                    </td>
                      
                    </tr>
                    @endforeach
                    @endif
                                              </tbody> 
            </table>
              <div class="flex justify-end">

                <div class="mt-5">
                    <div class="flex justify-between w-[260px] items-center mt-4">
                        @if($doctors->onFirstPage())
                            <span class="text-gray-500 px-4 py-1 text-sm bg-gray-200 rounded">Previous</span>
                        @else
                            <a href="{{ $doctors->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}" 
                               class="px-4 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
                                Previous
                            </a>
                        @endif
                
                        <span class="text-gray-700">
                            Page {{ $doctors->currentPage() }} of {{ $doctors->lastPage() }}
                        </span>
                
                        @if ($doctors->hasMorePages())
                            <a href="{{ $doctors->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}" 
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
   
</body>
@section('script')
@stop
@stop
</html>
