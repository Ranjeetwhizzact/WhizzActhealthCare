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

                @if(session('status') == 'success')
                    <div class="alert alert-success">
                     <h5 class="text-green-500">{{ session('success') }}</h5>
                    </div>
                @endif

         <form action="{{url('/schedule')}}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
            <div class="grid grid-cols-1 gap-4 ">
                @csrf
                <div >
                        <div class="w-1/2 m-auto  shadow-md px-4 py-6 rounded-md grid grid-cols-2 gap-4">



                            <!-- Last Name -->
                            {{-- <div class="col-span-2 md:col-span-1">
                                <input type="hidden" name="id" value="{{isset($schedule->id)?$schedule->id:''}}" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="Insurance campany Name">
                                <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 capitalize text-sm">Insurance campany Name</label>
                                <input type="text" name="company_name" value="{{isset($schedule->company_name)?$schedule->company_name:''}}" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="Insurance campany Name" required>
                            </div> --}}
                            <!-- Email -->
                            {{-- <div class="col-span-2 md:col-span-1">
                                <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm capitalize">Policy ID</label>
                                <input type="text" name="policy_id" value="{{isset($schedule->policy_id)?$schedule->policy_id:''}}" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="INS-XXXXXXXX-XX" pattern="^INS-\d{8}-HI$" required>
                            </div> --}}


                            <!-- Blood Group -->

                            <!-- Address (Full Width) -->
                            {{-- <div class="col-span-2 md:col-span-1">
                                <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm ">Reason for the Consultation</label>
                                <textarea class="w-full border border-gray-300 p-2 rounded-lg" rows="5" placeholder="Enter Reason for the Consultation" name="reason_consultation">{{isset($schedule->reason_consultation)?$schedule->reason_consultation:''}}</textarea>
                                <div class="mt-3">

                                    <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm capitalize">Reason for the Consultation if have any document</label>
                                    <input type="file" name="reason_consultation_docs" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="Documents">
                                </div>
                            </div> --}}
                            {{-- <div class="col-span-2 md:col-span-1">
                                <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm ">Existing Medical Conditions</label>
                                <textarea class="w-full border border-gray-300 p-2 rounded-lg" rows="5" placeholder="Enter Existing Medical Conditions" name="existing_medical_conditions">{{isset($schedule->existing_medical_conditions)?$schedule->existing_medical_conditions:''}}</textarea>
                                <div class="mt-3">

                                    <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm capitalize">Existing Medical Conditions if have any document</label>
                                    <input type="file" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="Documents" name="existing_medical_conditions_docs">
                                </div>
                            </div> --}}
                            {{-- <div class="col-span-2 md:col-span-1">
                                <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm ">Current Medications</label>
                                <textarea class="w-full border border-gray-300 p-2 rounded-lg" rows="5" placeholder="Enter Current Medications" name="current_medications">{{isset($schedule->current_medications)?$schedule->current_medications:''}}</textarea>
                                <div class="mt-3">

                                    <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm capitalize" >Current Medications if have any document</label>
                                    <input type="file" class="w-full border border-gray-300 p-2 rounded-lg" name="current_medications_docs" placeholder="Documents">
                                </div>
                            </div> --}}
                            {{-- <div class="col-span-2 md:col-span-1">
                                <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm ">Previous Consultations or Treatments</label>
                                <textarea class="w-full border border-gray-300 p-2 rounded-lg" rows="5" placeholder="Enter Previous Consultations or Treatments" name="previous_consultations">{{isset($schedule->previous_consultations)?$schedule->previous_consultations:''}}</textarea>
                                <div class="mt-3">

                                    <label class="block text-gray-700 font-mediublock text-gray-700 font-medium mb-2 text-sm capitalize">Previous Consultations or Treatments if have any document</label>
                                    <input type="file" class="w-full border border-gray-300 p-2 rounded-lg" placeholder="Documents" name="previous_consultations_docs">
                                </div>
                            </div> --}}


                            <div class="col-span-2 ">
                                <h5 class="ps-3 font-medium my-2 "> Schedule Call {{ isset($patient) ? 'For '. $patient->first_name . ' ' . $patient->last_name : '' }}</h5>
                                <input type="hidden" name="email"  id="clientemail" class="w-full border border-gray-300 p-2 rounded-lg">
                                  <div class="col-span-2" @if($patient) hidden @endif>

                                    <label for="patients-search" class="block text-gray-700 font-medium mb-2 text-sm">
                                        Proposal Number / Name / Email / Phone
                                    </label>
                                    <input
                                        type="text"
                                        id="patients-search"
                                        name="proposal_number"
                                        class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                        placeholder="Search by proposal number, name, email, or phone"
                                        required @if($patient) disabled @endif
                                    >
                                    <input type="hidden" id="patient-id" name="client_id" value="{{ isset($patient) ? $patient->id : '' }}" >
                                </div>
                                <div class="px-2">
                                    <label class="font-medium ps-2 text-sm inline-block w-full my-2">Appoint Patient</label>
                                    <input type="text" id="assign_patient_name" name="assign_patient_name" class="w-full border border-gray-300 p-2 rounded-lg" name="" placeholder="Search Patient" value="{{ isset($patient) ? $patient->first_name . ' ' . $patient->last_name : '' }}">
                                </div>

                            </div>

                            <div class="col-span-2">
                                <div class="px-2">
                                    <label for="" class="font-medium ps-2 text-sm inline-block w-full my-2">Appointment Date</label>
                                        <p> <input type="text"  name="date" id="date"  value="{{isset($schedule->appointment_date)?$schedule->appointment_date:''}}" class="appointdate w-full border border-gray-300 p-2 rounded-lg"></p>
                                   </div>
                            </div>
                            <div class="col-span-2 ">
                                <div class="p-3">
                                    <h5 class="font-medium text-sm">Appointment Time Slot</h5>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-3">
                                        <div>
                                            <input type="radio" name="time" id="time" value="09:45" class="time-radiobutton peer hidden">
                                            <label for="time" class="block peer-checked:bg-red-500 peer-checked:text-white bg-red-100 text-sm py-2 px-3 rounded-md text-red-500 w-full text-center cursor-pointer">9:45 AM</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="time" id="time1" value="10:15" class="time-radiobutton peer hidden">
                                            <label for="time1" class="block peer-checked:bg-red-500 peer-checked:text-white bg-red-100 text-sm py-2 px-3 rounded-md text-red-500 w-full text-center cursor-pointer">10:15 AM</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="time" id="time2" value="11:45" class="time-radiobutton peer hidden">
                                            <label for="time2" class="block peer-checked:bg-red-500 peer-checked:text-white bg-red-100 text-sm py-2 px-3 rounded-md text-red-500 w-full text-center cursor-pointer">11:45 AM</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="time" id="time3" value="12:15" class="time-radiobutton peer hidden">
                                            <label for="time3" class="block peer-checked:bg-red-500 peer-checked:text-white bg-red-100 text-sm py-2 px-3 rounded-md text-red-500 w-full text-center cursor-pointer">12:15 PM</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="time" id="time4" value="13:00" class="time-radiobutton peer hidden">
                                            <label for="time4" class="block peer-checked:bg-red-500 peer-checked:text-white bg-red-100 text-sm py-2 px-3 rounded-md text-red-500 w-full text-center cursor-pointer">01:00 PM</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="time" id="time5" value="14:30" class="time-radiobutton peer hidden">
                                            <label for="time5" class="block peer-checked:bg-red-500 peer-checked:text-white bg-red-100 text-sm py-2 px-3 rounded-md text-red-500 w-full text-center cursor-pointer">2:30 PM</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="time" id="time6" value="15:00" class="time-radiobutton peer hidden">
                                            <label for="time6" class="block peer-checked:bg-red-500 peer-checked:text-white bg-red-100 text-sm py-2 px-3 rounded-md text-red-500 w-full text-center cursor-pointer">3:00 PM</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="time" id="time7" value="15:40" class="time-radiobutton peer hidden">
                                            <label for="time7" class="block peer-checked:bg-red-500 peer-checked:text-white bg-red-100 text-sm py-2 px-3 rounded-md text-red-500 w-full text-center cursor-pointer">3:40 PM</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="time" id="time8" value="16:10" class="time-radiobutton peer hidden">
                                            <label for="time8" class="block peer-checked:bg-red-500 peer-checked:text-white bg-red-100 text-sm py-2 px-3 rounded-md text-red-500 w-full text-center cursor-pointer">4:10 PM</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="time" id="time9" value="16:45" class="time-radiobutton hidden  peer">
                                            <label for="time9" class="block peer-checked:bg-red-500 peer-checked:text-white bg-red-100 text-sm py-2 px-3 rounded-md text-red-500 w-full text-center cursor-pointer">4:45 PM</label>
                                        </div>
                                    </div>


                                   </div>

                            </div>
                            <div class="col-span-2 px-2">
    <label for="appointment-type" class="block text-gray-700 font-medium mb-2 text-sm">
        Appointment Type
    </label>
    <select 
        id="appointment-type" 
        name="appointment_type" 
        class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
        required
    >
        <option value="" disabled selected>Select Appointment Type</option>
        <option value="online">Online</option>
        <option value="offline">Offline / In-person</option>
    </select>
</div>

                            <div class="col-span-2">

                                <div class="px-2">
                                 <label for="" class="font-medium ps-2 text-sm inline-block w-full my-2">Appoint Doctor</label>
                                 <select id="doctorSelect" class="py-2 border rounded-md w-full" name="doctor_id">

                                     <option value="">Choose Doctor</option>
                                     @if(!empty($doctorList) && count($doctorList) > 0)
                                     @foreach ($doctorList as $doctor)
                                         <option value="{{ $doctor['doctor_id'] }}">{{ $doctor['first_name'] }} {{ $doctor['last_name'] }}</option>
                                     @endforeach
                                 @endif

                                </select>
                                </div>

                                <div class="mt-4 text-centercol-span-2 hidden md:block">
                                    <button type="submit" class="bg-black text-white px-6 py-2 rounded-lg  hover:bg-gray-500">Submit</button>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                    </div>

            </form>
            <form >
                {{-- <label for="date">Select Date:</label>
                <input type="date" id="date" name="date"><br><br> --}}

                {{-- <label>Select Available Time Slots:</label><br>
                <input type="checkbox" class="time-checkbox" value="09:00"> 09:00 AM<br>
                <input type="checkbox" class="time-checkbox" value="12:30"> 12:30 AM<br>
                <input type="checkbox" class="time-checkbox" value="19:50"> 19:50 PM<br>
                <input type="checkbox" class="time-checkbox" value="19:00"> 07:00 PM<br> --}}

                <br>

{{--
                <h3>Available Doctors:</h3>
                <div id="result"></div> --}}

            </form>
            </div>
        </div>
    </div>

</body>
@section('script')
<script>
$(function() {
// Fetch patient data and populate fields
function fetchPatientByProposal(query) {
    $.ajax({
        url: "/searchpatients",
        type: "GET",
        data: { query: query },
        dataType: "json",
        success: function(data) {
            if (data && data.length > 0) {
                let selectedPatient = data.find(patient =>
                    patient.proposal_number === query ||
                    patient.email === query ||
                    patient.phone_number === query ||
                    patient.first_name.toLowerCase() === query.toLowerCase()
                );

                if (!selectedPatient) selectedPatient = data[0];

                $("#patient-id").val(selectedPatient.id);
                $("#assign_patient_name").val(selectedPatient.first_name + " " + selectedPatient.last_name);
                // $("#patients-search").val(selectedPatient.proposal_number);
                // $("#clientemail").val(selectedPatient.email);
                // $("#clientphone").val(selectedPatient.phone);
                // $("#date").val(selectedPatient.providedate);
            } else {
                console.log("No patient data returned.");
            }
        },
        error: function(xhr, status, error) {
            console.log("AJAX Error:", error);
        }
    });
}

$("#patients-search").autocomplete({
    source: function(request, response) {
        $.ajax({
            url: "/searchpatients",
            type: "GET",
            data: { term: request.term },
            dataType: "json",
            success: function(data) {
                response($.map(data, function(patient) {
                    return {
                        label: `  `,
                        value: patient.phone, // input shows phone number
                        data: patient // store full patient data
                    };
                }));
            },
            error: function(xhr, status, error) {
                console.log("AJAX Error:", error);
            }
        });
    },
    minLength: 1,
    select: function(event, ui) {
        // Directly use the selected patient's ID and details
        let selectedPatient = ui.item.data;

        $("#patient-id").val(selectedPatient.id);
        $("#assign_patient_name").val(selectedPatient.first_name + " " + selectedPatient.last_name);
        // $("#patients-search").val(selectedPatient.phone_number); // or any value you want to show
        // $("#clientemail").val(selectedPatient.email);
        // $("#clientphone").val(selectedPatient.phone);
        // $("#date").val(selectedPatient.providedate);
    }
});

// Customize dropdown style
$.ui.autocomplete.prototype._renderItem = function(ul, item) {
    return $("<li>")
        .append(` 
            <div class="bg-white flex hover:bg-blue-500 hover:text-white p-4 border border-gray-200 gap-4 rounded-md cursor-pointer">
                <div>
                    <strong class="text-sm text-gray-800">${item.data.first_name} ${item.data.last_name}</strong><br>
                    <span class="text-gray-600">Phone: ${item.data.phone}</span><br>
                    <span class="text-gray-600">Email: ${item.data.email}</span>
                </div>
            </div>
        `)
        .appendTo(ul);
};



    $("#assign_patient_name").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "/searchpatients",
                type: "GET",
                data: { term: request.term },
                dataType: "json",
                success: function(data) {
                    response($.map(data, function(patient) {
                        return {
                            label: patient.first_name + " " + patient.last_name,
                            // value: patient.proposal_number,
                            id: patient.id,
                            data: patient
                        };
                    }));
                },
                error: function(xhr, status, error) {
                    console.log("AJAX Error:", error);
                }
            });
        },
        minLength: 1,
        select: function(event, ui) {
            fetchPatientByProposal(ui.item.value);
        }
    });
});

$(function() {
$("#date").datepicker({
        minDate: 0
    });
    });

      $(document).ready(function () {
    let selectedDate = null;
    let selectedTime = null;

    // Fetch available doctors when a time radio button is selected
    $('.time-radiobutton').on('change', function () {
        selectedDate = $('#date').val();
        selectedTime = $(this).val();

        if (selectedDate) {
            console.log("Selected Date:", selectedDate); // Debugging output

            $.ajax({
                url: "/getavailabletimes",
                type: "GET",
                data: { date: selectedDate, time: selectedTime },
                dataType: "json",
                success: function (response) {
                    let doctorList = response.doctorList || [];
                    let $doctorDropdown = $('#doctorSelect');

                    $doctorDropdown.empty(); // Clear previous options

                    if (doctorList.length > 0) {
                        $doctorDropdown.append('<option value="">Select a Doctor</option>');
                        doctorList.forEach(function (doctor) {
                            let option = `<option value="${doctor.doctor_id}">${doctor.first_name} ${doctor.last_name}</option>`;
                            $doctorDropdown.append(option);
                        });
                    } else {
                        $doctorDropdown.append('<option value="">No doctors available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error Details:", {
                        status: status,
                        error: error,
                        responseText: xhr.responseText
                    });
                    alert("Error fetching available doctors. Please check the console.");
                }
            });
        }
    });

    // Capture selected date and time
    $(document).on('change', '.time-radiobutton', function () {
        selectedTime = $(this).val();

        if (selectedDate && selectedTime) {
            alert(`Date: ${selectedDate}\nTime: ${selectedTime} selected!`);
        }
    });
});


</script>
@stop
@stop
</html>
