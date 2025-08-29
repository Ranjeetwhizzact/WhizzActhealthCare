<input type="checkbox" id="sidebar-toggle" class="hidden">
<div class="sidebar fixed inset-y-0 left-0 bg-white text-gray-100 w-64 transition-all duration-300">
  <div class="bg-white pt-4 px-2 flex justify-between items-center border-b-2 pb-4 border-gray-100">
      <div class="flex items-center mt-1 ">
          <!-- Toggle button for collapsing the sidebar -->
          <label for="sidebar-toggle" class="text-2xl text-[#A1A1AA] h-10 hover:text-[#DC2626] transition-colors cursor-pointer ml-1 mr-4 mt-2">
              <i class="ri-menu-line"></i>
          </label>
          <!-- Logo (Image) -->
          <img src="{{url('/assests/img/honestlogo.png')}}" alt="Dashboard Logo" class="h-12 hidden sm:block dashboardlogo">

      </div>
     
  </div>

  <!-- Sidebar Navigation -->
  <nav class="mt-6">
    <ul class="space-y-0">
        @if(auth()->user()->role === 'superadmin')
        <li>
            <a href="{{url('/')}}" class="cursor-pointer  {{ request()->is('/') ? 'text-[#DC2626]' : 'text-[#A1A1AA]' }} flex items-center pl-3 py-2 hover:text-[#DC2626] transition-colors">
                <i class="ri-dashboard-line text-xl"></i>
                <span class="ml-5 navtext capitalize">Dashboard</span>
            </a>
        </li>
        @endif
         <li>
            <a href="{{url('assignpatients')}}" class="cursor-pointer  flex items-center pl-3 py-2 {{ request()->is('assignpatients') ? 'text-[#DC2626]' : 'text-[#A1A1AA]' }} hover:text-[#DC2626]  transition-colors">
                <i class="ri-group-line text-xl"></i>
                <span class="ml-5 navtext capitalize">Assigned Patients</span>
            </a>
        </li>
       
        @if(auth()->user()->role === 'superadmin')
        <li>
            <a href="{{ route('customers') }}" class="cursor-pointer  flex items-center pl-3 py-2 {{ request()->is('patient') ? 'text-[#DC2626]' : 'text-[#A1A1AA]' }} hover:text-[#DC2626]  transition-colors">
                <i class="ri-group-line text-xl"></i>
                <span class="ml-5 navtext capitalize">Patients</span>
            </a>
        </li>
        <li>
            <a href="{{url('doctor')}}" class="cursor-pointer  flex items-center pl-3 py-2 {{ request()->is('doctor') ? 'text-[#DC2626]' : 'text-[#A1A1AA]' }} hover:text-[#DC2626]  transition-colors">
                <i class="ri-stethoscope-line text-xl"></i>
                <span class="ml-5 navtext capitalize">Doctors</span>
            </a>
        </li>
      
        {{-- <li>
            <a href="{{url('report')}}" class="cursor-pointer {{ request()->is('report') ? 'text-[#DC2626]' : 'text-[#A1A1AA]' }} flex items-center pl-3 py-2 hover:text-[#DC2626] transition-colors">
                <i class="ri-file-chart-line text-xl"></i>
                <span class="ml-5 navtext capitalize">Report</span>
            </a>
        </li>  --}}
        
        <li>
            <a href="{{url('schedule')}}" class="cursor-pointer {{ request()->is('schedule') ? 'text-[#DC2626]' : 'text-[#A1A1AA]' }} flex items-center pl-3 py-2 hover:text-[#DC2626] transition-colors">
                <i class="ri-history-line"></i>
                <span class="ml-5 navtext capitalize">Schedules</span>
            </a>
        </li> 
        <li>
            <a href="{{url('user') }}" class="cursor-pointer {{ request()->is('register') ? 'text-[#DC2626]' : 'text-[#A1A1AA]' }} flex items-center pl-3 py-2 hover:text-[#DC2626] transition-colors">
                <i class="ri-user-add-line"></i>
                <span class="ml-5 navtext capitalize">Users</span>
            </a>
        </li> 
        @endif
        <li>
            <form action="{{ route('logout') }}" method="POST"class="cursor-pointer fixed bottom-0 flex items-center pl-3 py-2 text-[#A1A1AA] hover:text-[#DC2626] transition-colors">
              @csrf
                <button class="flex whitespace-nowrap" type="submit">
                    <i class="ri-logout-box-line inline-block text-xl me-5"></i><span class="navtext capitalize">Logout</span>
                </button>
            </form>
        </li>
    </ul>
  </nav>
</div>
