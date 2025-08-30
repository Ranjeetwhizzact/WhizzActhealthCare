<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Helpers\ZoomHelper;
use Illuminate\Http\Request;
use App\Services\ZoomServices;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Report;
use App\Mail\MeetingMail;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class AppointmentController extends Controller
{
    protected $zoomService;

    public function index(Request $request){
        $patient_id = $request->query('id');
        $patient = null;
        if($patient_id) {
            $decoded = Hashids::decode($patient_id);
            $patientId = $decoded[0];
            $patient = Patient::where('id', $patientId)->first();
        }
        //return $patient;
        return view('schedule', compact('patient'));
    }

    public function __construct(ZoomServices $zoomService)
    {
        $this->zoomService = $zoomService;
    }
public function checkdoctor(Request $request)
{
    
    $request->validate([
        'date' => 'required|date',               
        'time' => 'required|date_format:H:i',    
    ]);

    $selectDate = $request->date; 
    $selectTime = $request->time; 

    $weekday = strtolower(Carbon::parse($selectDate)->format('l'));

    $doctors = Doctor::all()->filter(function ($doctor) use ($weekday, $selectTime) {
        $availableDays = json_decode($doctor->available_days, true);

        if (!isset($availableDays[$weekday])) {
            return false;
        }

        // Handle both formats: "start"/"end" OR "start_time"/"end_time"
        $dayData  = $availableDays[$weekday];
        $startKey = $dayData['start'] ?? $dayData['start_time'] ?? null;
        $endKey   = $dayData['end'] ?? $dayData['end_time'] ?? null;

        if (!$startKey || !$endKey) {
            return false; // timings not set
        }

        $startTime = Carbon::parse($startKey)->format('H:i');
        $endTime   = Carbon::parse($endKey)->format('H:i');

        // If requested time is outside available hours, skip doctor
        if ($selectTime < $startTime || $selectTime > $endTime) {
            return false;
        }

        return true;
    });

    // ✅ Filter out doctors who already have an appointment at that time
    $availableDoctors = $doctors->filter(function ($doctor) use ($selectDate, $selectTime) {
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('start_time', $selectDate)
            ->get();

        foreach ($appointments as $appointment) {
            $startTime = Carbon::parse($appointment->start_time)->format('H:i');
            $endTime   = Carbon::parse($appointment->end_time)->format('H:i');

            Log::info('Appointment details', [
                'doctor_id'        => $doctor->user_id,
                'appointment_start'=> $startTime,
                'appointment_end'  => $endTime,
                'requested_time'   => $selectTime
            ]);

            // If requested time falls within an existing appointment, skip doctor
            if ($selectTime >= $startTime && $selectTime < $endTime) {
                return false;
            }
        }

        return true;
    });

    // ✅ Build response list
    $doctorList = $availableDoctors->map(function ($doctor) {
        return [
            'first_name' => $doctor->first_name,
            'last_name'  => $doctor->last_name,
            'doctor_id'  => $doctor->user_id,
        ];
    })->values(); // ensures clean JSON array

    return response()->json(['doctorList' => $doctorList], 200);
}



public function scheduleCall(Request $request)
{
    Log::info('scheduleCall initiated', ['request' => $request->all()]);

    try {
        // Handle dynamic date format parsing
        if ($request->filled('date')) {
            $inputDate = $request->date;
            $formattedDate = null;

            // Accept YYYY-MM-DD or MM/DD/YYYY
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $inputDate)) {
                $formattedDate = $inputDate;
            } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $inputDate)) {
                try {
                    $formattedDate = Carbon::createFromFormat('m/d/Y', $inputDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::error('Date format conversion failed', ['input_date' => $inputDate, 'exception' => $e->getMessage()]);
                    return back()->with('error', 'Invalid date format. Please use MM/DD/YYYY or YYYY-MM-DD.');
                }
            } else {
                Log::error('Unrecognized date format', ['input_date' => $inputDate]);
                return back()->with('error', 'Invalid date format. Please use MM/DD/YYYY or YYYY-MM-DD.');
            }

            $request->merge(['date' => $formattedDate]);
        } else {
            return back()->with('error', 'Date is required.');
        }

        // Validate input
        try {
            $request->validate([
                'client_id' => 'required|exists:patients,id',
                'doctor_id' => 'required|exists:doctors,user_id',
                'date' => 'required|date',
                'time' => 'required',
                'appointment_type' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors());
        }

        $startTime = $request->date . ' ' . $request->time . ':00';
        $endTime = date('Y-m-d H:i:s', strtotime($startTime . ' +30 minutes'));

        Log::info('Start and end time computed', ['start' => $startTime, 'end' => $endTime]);

        $doctor = Doctor::where('user_id', $request->doctor_id)->firstOrFail();
        $client = Patient::findOrFail($request->client_id);

        Log::info('Doctor and client fetched', ['doctor_id' => $doctor->user_id, 'client_id' => $client->id]);

        // Create Zoom meeting only for online appointments
        $zoomMeeting = null;
        if ($request->appointment_type == "online") {
            $zoomMeeting = $this->zoomService->createMeeting($startTime, $doctor->email, $client->email);
            Log::info('Zoom meeting created', ['zoomMeeting' => $zoomMeeting]);
        }

        // Prepare appointment data
        $appointmentData = [
            'doctor_id' => $doctor->user_id,
            'client_id' => $client->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'appointment_type' => $request->appointment_type,
            'status' => 'scheduled',
            'doctor_access_key' => Str::random(10),
        ];

        // Add Zoom data only for online appointments
        if ($request->appointment_type == "online" && $zoomMeeting) {
            $appointmentData['zoom_meeting_id'] = $zoomMeeting['id'];
            $appointmentData['zoom_join_url'] = $zoomMeeting['join_url'];
            $appointmentData['zoom_start_url'] = $zoomMeeting['start_url'];
            $appointmentData['zoom_passcode'] = $zoomMeeting['password'];
        }

        // Create or update appointment
        $appointment = Appointment::where('client_id', $client->id)
            ->where('status', '!=', 'completed')
            ->first();

        if ($appointment) {
            // Update existing appointment
            $appointment->update($appointmentData);
            Log::info('Appointment updated', ['appointment_id' => $appointment->id]);
        } else {
            // Create new appointment
            $appointment = Appointment::create($appointmentData);
            Log::info('Appointment created', ['appointment_id' => $appointment->id]);
        }

        // Update client status
        $client->status = 'scheduled';
        $client->save();
        Log::info('Client status updated');

        // Send notifications only for online appointments
        if ($request->appointment_type == "online") {
            $honestdomain = env('APP_URL');
            $meetingLink = "{$honestdomain}/join-meeting/{$zoomMeeting['id']}";
            $mobile = ltrim($client->phone, '0');
            $assign_patient_name = $client->first_name . ' ' . $client->last_name;

            // Send Email
            $emailResponse = Http::withHeaders([
                'accept' => 'application/json',
                'authkey' => '400026Aum41tS2Xqb68664708P1',
                'content-type' => 'application/json',
            ])->post('https://control.msg91.com/api/v5/email/send', [
                'recipients' => [[
                    'to' => [[
                        'name' => $assign_patient_name,
                        'email' => $client->email,
                    ]],
                    'cc' => [[
                        'name' => 'WhizzCare',
                        'email' => 'no_reply@whizzact.com'
                    ]],
                    'variables' => [
                        'VAR1' => $assign_patient_name,
                        'VAR2' => $request->date,
                        'VAR3' => $request->time,
                        'VAR4' => $meetingLink,
                    ]
                ]],
                'from' => [
                    'name' => 'WhizzCare',
                    'email' => 'whizzcare@email.whizzactsolutions.com'
                ],
                'domain' => 'email.whizzactsolutions.com',
                'reply_to' => [[
                    'email' => 'no_reply@whizzact.com'
                ]],
                'attachments' => [],
                'template_id' => 'honest_availability_submitted_confirmation'
            ]);

            // Send SMS
            $smsResponse = Http::withHeaders([
                'authkey' => '447742AMCfHYvVexw68079d89P1',
                'Content-Type' => 'application/json',
            ])->post('https://control.msg91.com/api/v5/flow', [
                'template_id' => '68441d65d6fc05068303a682',
                'short_url' => 1,
                'recipients' => [[
                    'mobiles' => "91{$mobile}",
                    'name' => $assign_patient_name,
                    'date' => $request->date,
                    'time' => $request->time,
                    'link' => $meetingLink,
                ]]
            ]);

            Log::info('SMS response', ['response' => $smsResponse->body()]);

            if ($emailResponse->successful() && $smsResponse->successful()) {

                 if ($appointment) {
                    $appointment->update($appointmentData);
                    Log::info('Appointment updated', ['appointment_id' => $appointment->id]);
                    } else {        
                        $appointment = Appointment::create($appointmentData);
                        Log::info('Appointment created', ['appointment_id' => $appointment->id]);
                    }

                Log::info('Both email and SMS sent successfully.');
                return back()->with('success', 'The video medical examination has been scheduled, and notifications were sent via SMS and email.');
            } else {
                Log::warning('Notification failed', [
                    'emailSuccess' => $emailResponse->successful(),
                    'smsSuccess' => $smsResponse->successful()
                ]);
                return back()->with('error', 'Appointment saved, but failed to send one or more notifications.');
            }
        } else {
            // For offline appointments
            return back()->with('success', 'Appointment has been scheduled successfully.');
        }

    } catch (\Throwable $e) {
        Log::error('Exception in scheduleCall', [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Something went wrong. Please try again later.');
    }
}

    public function joinMeeting(Request $request, $appointmentId)
    {
        $appointment = Appointment::where('zoom_meeting_id', $appointmentId)->firstOrFail();

        // Check if the user is a doctor or patient
        $isDoctor = ($request->has('doctor_access') && $request->doctor_access == $appointment->doctor_access_key);
        $userName = '';
        $userEmail = '';

        $patientId = $appointment->client_id;
        if ($isDoctor) {

            $doctor = Doctor::where('user_id', $appointment->doctor_id)->firstOrFail();

            $userName =  $doctor->first_name . " " . $doctor->last_name;
            $userEmail = $doctor->email;
        } else {
            $patient = Patient::findOrFail($appointment->client_id);
            $userName =  $patient->first_name . " " . $patient->last_name;
            $userEmail = $patient->email;
        }

        // Initialize Zoom Helper
        $zoomHelper = new ZoomHelper();

        // Check if meeting exists before starting recording
        $meetingDetails = $this->zoomService->getMeetingDetails($appointment->zoom_meeting_id);
        if (!isset($meetingDetails->original['id'])) {
            return response()->json(['error' => 'Zoom meeting not found'], 404);
        }
        // dd($meetingDetails);



        // Start Zoom Auto-Recording
        // $this->zoomService->startZoomRecording($appointment->zoom_meeting_id);

        return view('zoomMeetingView', [
            'meetingId' => $appointment->zoom_meeting_id,
            'passCode' => $appointment->zoom_passcode,
            'signature' => $zoomHelper->generateZoomSignature($appointment->zoom_meeting_id, $isDoctor ? 1 : 0),
            'userName' => $userName,
            'userEmail' => $userEmail,
            'isDoctor' => $isDoctor,
            'patientId'=> $patientId,
        ]);
    }


    public function storereport(Request $request)
    {
        // dd($request->all());
        // Check if the meeting exists
        $meetingDetails = $this->zoomService->getMeetingDetails($request->meeting_id);
        if ($meetingDetails instanceof \Illuminate\Http\JsonResponse && $meetingDetails->getStatusCode() === 404) {
            return response()->json(['error' => 'Zoom meeting not found'], 404);
        }
        // dd($meetingDetails);
        // End the Zoom Meeting (force logout participants)
        $this->zoomService->endMeeting($request->meeting_id);

        // Wait a few seconds to allow Zoom to process recording
        // sleep(5);

        // $recordingResponse = $this->zoomService->getMeetingRecordings($request->meeting_id);
        // $recordingUrl = $recordingResponse['recording_files'][0]['play_url'] ?? null;

        // dd($recordingResponse);

        // Save Report
        $report = new Report;
        $report->client_id = $request->client_id;

        $report->name = $request->name;
        $report->message = $request->message;
        // $report->recording_url = $recordingUrl;
        $report->created_by = Auth::user()->email;
        $report->updated_by = Auth::user()->email;
        $report->save();

        // Close the current window
        echo "<script>window.close();</script>";

        // if(isset($request->isDoctor)){
        //     return redirect('medical-history')->with('success', 'Call ended and report saved successfully.');
        // }else{
        // }

    }

    public function getRecording($meeting_id){
        $res = $this->zoomService->getMeetingRecordings($meeting_id);
        dd($res);
        return response()->json($res);
    }


}
