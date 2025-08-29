
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
    <div class="flex  h-screen bg-gray-100">
        <div class="w-full overflow-x-scroll  no-scrollbar bg-white px-2">
            <div class="flex justify-between  bg-white mt-7">
                <h5 class="text-lg capitalize font-semibold">&nbsp;Users</h5> <a href="{{ route('register') }}" class="d-inline-block p-2 rounded-md bg-emerald-400 text-sm"><i class="ri-user-add-line "></i>&nbsp;&nbsp;Add users</a>
            </div>
            {{-- <ul class="bg-white flex gap-5 border-b-2 border-gray-300 pb-1 w-full">
                <li><a href="" class="capitalize text-sm text-gray-500 hover:text-black transition-colors pb-[6px]  hover:border-b-2 hover:border-black whitespace-nowrap">All</a></li>
                <li><a href="" class="capitalize text-sm text-gray-500 hover:text-black transition-colors  pb-[6px]  hover:border-b-2 hover:border-black whitespace-nowrap">Completed</a></li>
                <li><a href="" class="capitalize text-sm text-gray-500 hover:text-black transition-colors  pb-[6px]  hover:border-b-2 hover:border-black whitespace-nowrap">Pending</a></li>
                <li><a href="" class="capitalize text-sm text-gray-500 hover:text-black transition-colors  pb-[6px]  hover:border-b-2 hover:border-black whitespace-nowrap">Rejected</a></li>
                <li><a href="" class="capitalize text-sm text-gray-500 hover:text-black transition-colors  pb-[6px]  hover:border-b-2 hover:border-black whitespace-nowrap">Re-Scheduled</a></li>

               
            </ul> --}}
            <table class="text-xs w-full rounded-2xl mt-4">
                <thead class="capitalize font-medium py-3 w-full  text-black bg-emerald-100">
                    <th class="p-2 text-start ">Sr.no</th>
                    <th class="p-2 text-start capitalize border whitespace-nowrap">name</th>
                    <th class="p-2 text-start capitalize border whitespace-nowrap">Email </th>
                    <th class="p-2 text-start capitalize border whitespace-nowrap">Role </th>

                    {{-- <th class="p-2 text-start capitalize border whitespace-nowrap">Address</th> --}}
                    <th class="p-2 text-start capitalize border whitespace-nowrap">Action</th>
                </thead>
                <tbody>
                    {{-- @if($patients)
                    @foreach ($patients as $i)
                        
                    <tr>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">1</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{$i->first_name }}&nbsp;{{$i->last_name}}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">DR.Adity</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap text-yellow-500 font-medium">Pending</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">22 Jan 2025</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">13:45 PM</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">
                            <a href="" id="modalToggle">View</a>,
                            <a href="">Report</a>,
                            <a href="">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                    @endif --}}
                    @if($user->count())
                    @php
                        $cp = $user->currentPage();
                        $perpage = $user->perPage();
                        $startnumber =($cp - 1) * $perpage;
                    @endphp
                    @foreach ($user as $i => $u )
                        
                    <tr  >
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $startnumber + $i + 1}}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{$u->name}}</td>
                        {{-- <td class="p-2 text-start capitalize border whitespace-nowrap">DR.Adity</td> --}}
                        <td class="p-2 text-start border whitespace-nowrap  font-medium">{{$u->email}}</td>
                        <td class="p-2 text-start border whitespace-nowrap  font-medium capitalize">{{$u->role}}</td>

                        {{-- <td class="p-2 text-start capitalize border whitespace-nowrap">{{$p->address}}</td> --}}
                        <td class="p-2 text-start capitalize border whitespace-nowrap flex gap-2">
                            {{-- <button type="button" class="view-btn modalToggle px-3 py-1 rounded-full bg-green-500 text-white hover:bg-green-400" data-id="{{ $u->id }}" >View</button> --}}
                         
                            <a href="{{ route('edituser', ['id' => $u->id]) }}" class="bg-yellow-500 text-white rounded-full px-3 py-1 hover:bg-yellow-400">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                  
                </tbody> 
            </table>
            <div class="mt-5">
                <div class="flex justify-between w-[260px] items-center mt-4">
               @if($user->onFirstPage())
               <span class="text-gray-500 px-4 py-1 text-sm bg-gray-200 rounded">Previous</span>
               @else
               <a href="{{ $user->previousPageUrl() }}" class="px-4 py-1 bg-red-500 text-white  text-sm rounded hover:bg-red-600">Previous</a>
               @endif
               <span class="text-gray-700">
                Page {{ $user->currentPage() }} of {{ $user->lastPage() }}
            </span>
            @if ($user->hasMorePages())
            <a href="{{ $user->nextPageUrl() }}" class="px-4 py-1 bg-red-500  text-sm text-white rounded hover:bg-red-600">Next</a>
        @else
            <span class="text-gray-500 px-4 py-1 bg-gray-200 rounded  text-sm">Next</span>
        @endif
            </div>
            
        </div>
    </div>
</body>
@section('script')
@stop
@stop
</html>
