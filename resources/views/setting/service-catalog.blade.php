@extends('layouts.app')
@section('title','Honest | Home ')
@section('content')
    <div class="flex h-screen divide-x-2 divide-gray-100 ">
        <!-- Sidebar -->
        @include('common.sidenav')

        <div class="main-content flex-1 ml-64 transition-all duration-300">
           @include('common.header')
            <div class="p-5 bg-white">
                @if(auth()->user()->role === 'superadmin')
                <div class="flex justify-between  bg-white">
                    <h5 class="text-lg capitalize font-semibold ">&nbsp;Service Catalog</h5> <a href="{{url('create/service-catalog')}}" class="d-inline-block p-2 rounded-md bg-emerald-400 text-sm text-white" hidden><i class="ri-add-line"></i>&nbsp;&nbsp;Add Service</a>
                </div>
                @endif

              <table class="text-xs w-full rounded-2xl mt-4">
                <thead class="capitalize font-medium py-3 w-full text-gray-800 bg-emerald-100">
                   <tr>
                        <th class="p-2 text-start capitalize border whitespace-nowrap">Code</th>
                        <th class="p-2 text-start capitalize border whitespace-nowrap">Name</th>
                        <th class="p-2 text-start capitalize border whitespace-nowrap">Category Name</th>
                        <th class="p-2 text-start capitalize border whitespace-nowrap">Price</th>
                        <th class="p-2 text-start capitalize border whitespace-nowrap">Unit</th>
                        <th class="p-2 text-start capitalize border whitespace-nowrap" hidden>Action</th>
                   </tr>
                </thead>
                <tbody>
                    @foreach($serviceCatalog as $service)
                    <tr>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $service->code }}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $service->name }}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $service->category_name }}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $service->price }}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $service->unit }}</td>

                        <td class="p-2 text-start capitalize border whitespace-nowrap" hidden>
                            <a href="{{ url('edit/service/'.$service->id) }}" class="text-blue-500">Edit</a>
                            <form action="{{ url('delete/service/'.$service->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
                <div class="flex justify-end">
                    <div class="mt-5">
                        {{ $serviceCatalog->links() }}
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
