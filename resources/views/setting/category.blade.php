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
                    <h5 class="text-lg capitalize font-semibold ">&nbsp;Categories</h5> <a href="{{url('create/category')}}" class="d-inline-block p-2 rounded-md bg-emerald-400 text-sm text-white" hidden><i class="ri-add-line"></i>&nbsp;&nbsp;Add Category</a>
                </div>
                @endif

              <table class="text-xs w-full rounded-2xl mt-4">
                <thead class="capitalize font-medium py-3 w-full text-gray-800 bg-emerald-100">
                   <tr>
                        <th class="p-2 text-start capitalize border whitespace-nowrap">Sr.no</th>
                        <th class="p-2 text-start capitalize border whitespace-nowrap">Code</th>
                        <th class="p-2 text-start capitalize border whitespace-nowrap">Name</th>
                        <th class="p-2 text-start capitalize border whitespace-nowrap">Status </th>
                        <th class="p-2 text-start capitalize border whitespace-nowrap" hidden>Action</th>
                   </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $loop->iteration }}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $category->code }}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">{{ $category->name }}</td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap">
                            @if($category->is_active)
                                <span class="text-green-500">Active</span>
                            @else
                                <span class="text-red-500">Inactive</span>
                            @endif
                        </td>
                        <td class="p-2 text-start capitalize border whitespace-nowrap" hidden>
                            <a href="{{ url('edit/category/'.$category->id) }}" class="text-blue-500">Edit</a>
                            <form action="{{ url('delete/category/'.$category->id) }}" method="POST" class="inline">
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
                        {{ $categories->links() }}
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
