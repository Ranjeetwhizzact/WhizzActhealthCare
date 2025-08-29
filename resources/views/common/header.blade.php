
<div class="bg-white h-[85px] border-b-2 border-gray-100 px-5 flex justify-between items-center">
    <div class="">
       <div class="">

           <div class="text-xs text-gray-400 capitalize">
               Good morning
           </div>
           <div class="text-base font-medium  capitalize">
              {{ Auth::user()->name }}
           </div>
       </div>
    </div>
    <div class="flex gap-4">
      
       <div class="hidden">
           <div class="w-10 h-10 flex justify-center items-center border-2 border-gray-100 rounded-full text-lg dropdown-button"><i class="ri-settings-5-line"></i></div>
           <div class="dropdown-menu hidden absolute mt-2 w-48 end-0 bg-white border border-gray-200 rounded-lg shadow-lg">
               <ul class="py-2 text-gray-700">
                   <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Option A</li>
                   <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Option B</li>
                   <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Option C</li>
               </ul>
           </div>
       </div>
       <div class="w-10 h-10 flex justify-center items-center border-2 border-gray-100 rounded-full"><i class="ri-notification-2-fill"></i></div>
       <div class="">
           <img src="{{url('/assests/img/avatar.png')}}" alt="" srcset="" class="w-9 h-9 rounded-full">
       </div>
    </div>
   </div>