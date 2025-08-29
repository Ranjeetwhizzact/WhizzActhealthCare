<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking</title>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-100 to-blue-300">
    <div class="bg-white p-8 rounded-2xl shadow-xl max-w-md w-full">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Book an Appointment</h2>

        @if(session('success'))
            <div class="mb-4 p-3 text-green-700 bg-green-100 rounded-md">
                <p class="text-center font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ url('/customermail') }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
            @csrf

            <input type="hidden" name="proposal_number" value="{{ $proposaldecode }}">
           
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700">Available date</label>
                <input type="text" id="avalibledate" name="avalibledate" class="mt-2 p-3 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" placeholder="YYYY-MM-DD" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700">Availble form Time</label>
                <input type="text" name="start_time" id="start-time" class="mt-2 p-3 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" placeholder="HH:MM" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700">Availble till Time</label>
                <input type="text" id="end-time" name="end_time" class="mt-2 p-3 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" placeholder="HH:MM" required>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-md">
                Submit
            </button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $("#avalibledate").datepicker({ dateFormat: 'yy-mm-dd' , minDate: 0});
            $("#start-time, #end-time").timepicker({ timeFormat: 'H:i' });
        });
    </script>
</body>
</html>
