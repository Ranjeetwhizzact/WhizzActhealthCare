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

           <div class="p-5 bg-white grid grid-cols-4 gap-7">

            <form action="{{url('/storepatient')}}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" class="col-span-4">
                @csrf

                <div class="grid grid-cols-1 gap-4 lg:w-full m-auto">
                    <div >
                        <div class=" ">
                            <div class="w-full bg-gray-100 rounded-full col-span-4 ">


                            </div>
                            <div >
                                <div class="grid  grid-cols-4 mt-5 gap-4  ">
                                    <h5 class="col-span-4 text-lg font-medium">Personl Information</h5>
                                    {{-- <div class="col-span-4 ">
                                        <div class="text-lg font-semibold ">Add patients</div>
                                        <label for="profile" class="border-2 w-24 h-24 rounded-full inline-block my-3"><img src="{{isset($patient->profile_img)?$patient->profile_img:'assests\img\avatar.png'}}" alt="assests\img\avatar.png" srcset="" class="w-full h-full rounded-full uploadimg"></label>
                                        <input type="file" name="profile_img" class="w-full border border-gray-300 p-2 rounded-lg hidden" placeholder="John" id="profile">
                                    </div> --}}
                                    <!-- First Name -->
                                    <input type="hidden" name="id" value="{{isset($patient->id)?$patient->id:''}}" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="John">
                                    <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                        <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">First Name</label>
                                        <input type="text" name="first_name" value="{{isset($patient->first_name)?$patient->first_name:''}}" class="w-full border border-gray-300 p-2 rounded-lg textinput" placeholder="John" required>

                                    </div>
                                    <!-- Last Name -->
                                    <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                        <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Last Name</label>
                                        <input type="text" name="last_name" value="{{isset($patient->last_name)?$patient->last_name:''}}" class="w-full border border-gray-300 p-2 rounded-lg textinput" placeholder="Doe" required>
                                    </div>
                                    <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                        <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Gender</label>
                                        <select class="w-full border border-gray-300 p-2 rounded-lg" name="gender">
                                            <option value="male"  {{ isset($patient->male) && $patient->male == "male" ? 'selected' : '' }}>Male</option>
                                            <option value="female"  {{ isset($patient->female) && $patient->female == "female" ? 'selected' : '' }}>Female</option>
                                            <option value="other"  {{ isset($patient->gender) && $patient->gender == "other" ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <!-- Marital Status -->
                                    <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                        <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Marital Status</label>
                                        <select class="w-full border border-gray-300 p-2 rounded-lg" name="marital_status">
                                            <option value="single" {{ isset($patient->marital_status) && $patient->marital_status == "single" ? 'selected' : '' }}>Single</option>
                                            <option value="married" {{ isset($patient->marital_status) && $patient->marital_status == "married" ? 'selected' : '' }}>Married</option>
                                            <option value="divorced" {{ isset($patient->marital_status) && $patient->marital_status == "divorced" ? 'selected' : '' }}>Divorced</option>
                                        </select>
                                    </div>
                                     <!-- DOB -->
                                    <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                        <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Date of Birth</label>
                                        <input type="date" name="dob" value="{{isset($patient->dob)?$patient->dob:''}}" id="customer_dob" class="w-full border border-gray-300 p-2 rounded-lg">
                                    </div>
                                     <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                        <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Email</label>
                                        <input type="email" name="email" value="{{isset($patient->email)?$patient->email:''}}" class="w-full border border-gray-300 p-2 rounded-lg emailid" placeholder="email@whizzcare.com" required>
                                    </div>
                                    <!-- Phone -->
                                     <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                        <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Phone</label>
                                        <input type="tel" name="phone" value="{{isset($patient->phone)?$patient->phone:''}}" class="w-full border border-gray-300 p-2 rounded-lg phonenumber" placeholder="XXXXXXXXXX" pattern="[987]\d{9}" required>
                                    </div>
                                <!-- Blood Group -->
                                     <div class="col-span-4 md:col-span-2">
                                        <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm ">Patient Address</label>
                                        <textarea class="w-full border border-gray-300 p-2 rounded-lg" rows="5" name="address" value="" placeholder="Patient address with city, state, landmark, country and pincode" required>{{isset($patient->address)?$patient->address:''}}</textarea>
                                    </div>
                                    <div class="col-span-4 md:col-span-2">
                                        <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm ">Health Problems</label>
                                        <textarea class="w-full border border-gray-300 p-2 rounded-lg" rows="5" name="health_problems" value="" placeholder="Health Problem" required>{{isset($patient->health_problems)?$patient->health_problems:''}}</textarea>
                                    </div>
                                     <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                        <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Document Type</label>
                                        <select class="w-full border border-gray-300 p-2 rounded-lg" name="id_type" >
                                            <option value="Aadhar Card"{{ isset($patient->id_type) && $patient->id_type == "Aadhar Card" ? 'selected' : '' }}>Aadhar card</option>
                                            <option value="Pen Card"{{ isset($patient->id_type) && $patient->id_type == "Pen Card" ? 'selected' : '' }}>Pen Card</option>
                                            <option value="Driving Licence"{{ isset($patient->id_type) && $patient->id_type == "Driving Licence" ? 'selected' : '' }}>Driving Licence</option>


                                        </select>
                                    </div>

                                     <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                        <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Upload Document</label>
                                        <input type="file" name="id_document" value="{{isset($patient->id_document)?$patient->id_document:''}}" class="w-full border border-gray-300 p-2 rounded-lg phonenumber" placeholder="Third Party Administrator name">
                                    </div>

                                    <div class="col-span-4">
                                        <h5 class="font-bold">Insurance Company Info</h5>
                                        </div>
                                         <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                            <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Third-Party Administrator</label>
                                            <input type="tel" name="third_party_administrator" value="{{isset($patient->third_party_administrator)?$patient->third_party_administrator:''}}" class="w-full border border-gray-300 p-2 rounded-lg phonenumber" placeholder="Third Party Administrator name">
                                        </div>
                                         <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                            <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Insurance Company Name</label>
                                            <input type="text" name="insurance_company_name" value="{{isset($patient->insurance_company_name)?$patient->insurance_company_name:''}}" class="w-full border border-gray-300 p-2 rounded-lg phonenumber" placeholder="Insurance Company Name">
                                        </div>
                                         <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                            <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Insurance Company Email</label>
                                            <input type="email" name="insurance_company_email" value="{{isset($patient->insurance_company_email)?$patient->insurance_company_email:''}}" class="w-full border border-gray-300 p-2 rounded-lg phonenumber" placeholder="Insurance Company Email">
                                        </div>
                                         <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                            <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Proposal Number</label>
                                            <input type="text" name="proposal_number" value="{{isset($patient->proposal_number)?$patient->proposal_number:''}}" class="w-full border border-gray-300 p-2 rounded-lg phonenumber" placeholder="Proposal number" >
                                        </div>
                                         <div class="col-span-4 sm:col-span-3 md:col-span-2 lg:col-span-1">
                                            <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm">Upload Pdf</label>
                                            <input type="file" name="documents" value="{{isset($patient->documents)?$patient->documents:''}}" class="w-full border border-gray-300 p-2 rounded-lg phonenumber" placeholder="Proposal number">
                                        </div>
                                </div>
                         </div>





                                <!-- Address (Full Width) -->

                                <!-- Submit Button -->
                                <div class="mt-4 text-centercol-span-2 hidden md:block">
                                    <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg  hover:bg-gray-500">Submit</button>
                                </div>
                            </div>


                        </div>

            </form>
        </div>
        {{-- <div class="col-span-2">
            <div class="bg-white rounded-lg  w-full p-6">

                <!-- Modal Header -->
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="text-xl font-semibold">log Form</h2>
                    <button id="closeModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <!-- Form -->
                <form id="patientFormlog"   enctype="multipart/form-data" accept-charset="UTF-8" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" name="name" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="message" id="message" rows="3" class="w-full px-3 py-2 border rounded-md focus:ring focus:ring-blue-200"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" id="closeModalBtn" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Submit
                        </button>
                    </div>
                </form>

            </div>
        </div> --}}
    </div>
    </div>
   {{-- model section --}}
   <section>
    <div id="myModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6">

            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b pb-2">
                <h2 class="text-xl font-semibold">Responsive Form</h2>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>



        </div>
    </div>
   </section>
   {{-- model section --}}
</body>
@section('script')
<script>
 const dobInput = document.getElementById('customer_dob');

// Get today's date
const today = new Date();

// Calculate the minimum and maximum birthdates for 18 to 70 years old
const minDate = new Date(today.getFullYear() - 70, today.getMonth(), today.getDate());
const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

// Format dates as 'YYYY-MM-DD'
dobInput.min = minDate.toISOString().split('T')[0];
dobInput.max = maxDate.toISOString().split('T')[0];


    </script>
@stop
@stop
</html>
