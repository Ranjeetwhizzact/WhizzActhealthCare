@extends('layouts.app')
@section('title','Honest | Patients ')
@section('content')
    <div class="flex h-screen divide-x-2 divide-gray-100 ">
        <!-- Sidebar -->
        @include('common.sidenav')


        <!-- Main Content -->
        <div class="main-content flex-1 ml-64 transition-all duration-300">
            <!-- Welcome Section -->
           @include('common.header')
                    <div class="max-w-5xl mx-auto space-y-8 my-5">
            <div class="text-center">
                <h1 class="text-2xl font-semibold text-gray-800 capitalize">Prescription For {{$patient->first_name}} {{$patient->last_name}}</h1>
                <p class="text-gray-500 mt-2">Fill out physician and medication details below</p>
            </div>

            <form method="POST"  action="{{ url('/storerescription') }}" class="space-y-6" enctype="multipart/form-data" accept-charset="UTF-8">
                @csrf

                <!-- Physician Info Card -->
                <div class="bg-white shadow-lg rounded-2xl p-6 max-w-3xl mx-auto">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Physician Information</h2>
                    <div class="grid grid-cols-1  gap-4 ">
                        <input type="hidden" name="appointment_id" value="{{ old('appointment_id', $appointment->id ?? '') }}">
                        <input type="text" name="allergies" placeholder="Allergies" class="p-3 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full"  value="{{ old('allergies', $lastPrescription->allergies ?? '') }}"  required>
                        <textarea name="notable_condition" id="" cols="30" placeholder="Please Add Patient condition Here.." rows="10" name="notable_condition" class="p-3 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full"   >{{ old('notable_condition', $lastPrescription->notable_condition ?? '') }}</textarea>
                        {{-- <input type="text" name="physician_phone" placeholder="Phone" class="p-3 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full">
                        <input type="email" name="physician_email" placeholder="Email" class="p-3 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full">
                        <div class="flex flex-col">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Signature</label>
                            <input type="file" name="physician_signature" accept="image/*" class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400" id="signatureInput">
                            <img id="signaturePreview" src="#" alt="Signature Preview" class="mt-2 max-h-24 hidden rounded-lg border shadow" />
                        </div> --}}
                    </div>
                </div>

                <!-- Medications Card -->
             <div id="medications-wrapper" class="space-y-3 overflow-x-auto bg-white shadow-lg rounded-2xl p-6 max-w-5xl mx-auto">
                 <button type="button" id="add-medication" class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Add Medication</button>
    @if($lastPrescription && !empty($lastPrescription->medications))
    @foreach(json_decode($lastPrescription->medications, true) as $index => $med)
    <div class="medication-item grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-2 items-center">
        <input type="text" name="medications[{{ $loop->index }}][medication_name]"
               placeholder="Medication Name"
               class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full"
               value="{{ $med['medication_name'] ?? '' }}" required>

        <input type="text" name="medications[{{ $loop->index }}][purpose]"
               placeholder="Purpose"
               class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full"
               value="{{ $med['purpose'] ?? '' }}">

        <input type="text" name="medications[{{ $loop->index }}][dosage]"
               placeholder="Dosage"
               class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full"
               value="{{ $med['dosage'] ?? '' }}" required>

        <input type="text" name="medications[{{ $loop->index }}][route]"
               placeholder="Route"
               class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full"
               value="{{ $med['route'] ?? '' }}">

        <input type="text" name="medications[{{ $loop->index }}][frequency]"
               placeholder="Frequency"
               class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full"
               value="{{ $med['frequency'] ?? '' }}">

        <div>
            <button type="button" class="remove-medication bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition mt-2 sm:mt-0">
                <i class="ri-delete-bin-6-line"></i>
            </button>

        </div>
    </div>
@endforeach

    @else
        {{-- Default empty row if no old prescription --}}
        <div class="medication-item grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-2 items-center">
            <input type="text" name="medications[0][medication_name]" placeholder="Medication Name" class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full" required>
            <input type="text" name="medications[0][purpose]" placeholder="Purpose" class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full">
            <input type="text" name="medications[0][dosage]" placeholder="Dosage" class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full dosage-input" required>
            <input type="text" name="medications[0][route]" placeholder="Route" class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full">
            <input type="text" name="medications[0][frequency]" placeholder="Frequency" class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full">
            <div>
                <button type="button" class="remove-medication bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition mt-2 sm:mt-0"><i class="ri-delete-bin-6-line"></i></button>
                <button type="button" id="add-medication" class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"><i class="ri-add-fill"></i></button>
            </div>
        </div>
    @endif
</div>


                <div class="text-center mb-5">
                    <button type="submit" class="my-4 px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition w-full sm:w-auto">Submit Prescription</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('script')
<script>
$(function(){
    let medIndex = 1;

    $('#add-medication').click(function(){
        const html = `
        <div class="medication-item grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-2 items-center">
            <input type="text" name="medications[${medIndex}][medication_name]" placeholder="Medication Name" class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full" required>
            <input type="text" name="medications[${medIndex}][purpose]" placeholder="Purpose" class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full">
            <input type="text" name="medications[${medIndex}][dosage]" placeholder="Dosage" class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full dosage-input" required>
            <input type="text" name="medications[${medIndex}][route]" placeholder="Route" class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full">
            <input type="text" name="medications[${medIndex}][frequency]" placeholder="Frequency" class="p-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 w-full">
           <div>
            <button type="button" class="remove-medication bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition mt-2 sm:mt-0"><i class="ri-delete-bin-6-line"></i></button>
            </div>
        </div>`;
        $('#medications-wrapper').append(html);
        medIndex++;
    });

    $(document).on('click', '.remove-medication', function(){
        $(this).closest('.medication-item').remove();
    });

    $('#signatureInput').change(function(){
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                $('#signaturePreview').attr('src', e.target.result).removeClass('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

$(document).on('input', '.dosage-input', function() {
    let val = $(this).val().replace(/[^01]/g, '');
    val = val.substring(0, 3);
    if (val.length > 1) {
        val = val.split('').join('-');
    }
    $(this).val(val);
});

});

</script>
<script type="text/javascript">
    // const inputs = document.querySelectorAll('.dosage-input');
    // inputs.forEach(input => {
    //     input.addEventListener('input', function() {
    //         let val = this.value.replace(/[^01]/g, '');
    //         val = val.substring(0, 3);
    //         if (val.length > 1) {
    //             val = val.split('').join('-');
    //         }
    //         this.value = val;
    //     });
    // });
</script>



        </div>

    </div>




</body>


@stop
@stop
</html>
