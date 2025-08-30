<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;
use App\Http\Controllers\HomeController;

class ScheduleController extends Controller
{
    public function medicalHistory(Request $request) {
        $userEmail = Auth::user()->email;
        $userid = Auth::user()->id;
        $doctor = Doctor::where('email', $userEmail)->first();
        $setting = HomeController::setting();
        if($setting){
            $domain = $setting->domain;
        }

        $patientsQuery = DB::table('appointments as a')
            ->join('patients as p', 'a.client_id', '=', 'p.id')
            ->join('doctors as d', 'a.doctor_id', '=', 'd.user_id')
            ->where('a.zoom_meeting_id', '=', '')
            ->orderBy('a.id', 'desc')
            ->select(
                'p.id as patient_id',
                'p.first_name as patient_first_name',
                'p.last_name as patient_last_name',
                'p.dob as patient_dob',
                'p.blood_group as patient_blood_group',
                'p.gender as patient_gender',
                'p.marital_status as patient_marital_status',
                'p.email as patient_email',
                'p.phone as patient_phone',
                'p.address as patient_address',
                'a.status as patient_status',
                'd.first_name as doctor_first_name',
                DB::raw("DATE_FORMAT(a.start_time, '%h:%i %p') as schedule_time"),
                'a.id as id',
                'd.last_name as doctor_last_name',
                DB::raw("CONCAT('{$domain}/join-meeting/', COALESCE(a.zoom_meeting_id, ''), '?doctor_access=', COALESCE(a.doctor_access_key, '')) as meeting")
            );

        // Filter by status if provided
        $status = $request->get('status', '');
        if (!empty($status)) {
            $patientsQuery->where('a.status', $status);
        }

        // Filter for non-admin users
        if (Auth::user()->role != 'superadmin' && Auth::user()->role != 'admin') {
            $patientsQuery->where('a.doctor_id', $userid);
        }

        // Apply pagination
        $patients = $patientsQuery->paginate(10);

        // Transform paginated collection to encode hashed ID
        $patients->getCollection()->transform(function ($patient) {
            $hashedId = Hashids::encode($patient->id);
            $patient->hashed_id = $hashedId;
            return $patient;
        });

        $statuscolors = [
            'scheduled'   => 'text-yellow-500',
            'completed'   => 'text-green-500',
            'rescheduled' => 'text-blue-500',
            'canceled'    => 'text-red-500',
        ];

        // Return the view with paginated patients
        return view('assignpatients', ['patients' => $patients, 'statuscolors' => $statuscolors]);
    }

    public function videoConsultation(Request $request) {
        $userEmail = Auth::user()->email;
        $userid = Auth::user()->id;
        $doctor = Doctor::where('email', $userEmail)->first();
        $setting = HomeController::setting();
        if($setting){
            $domain = $setting->domain;
        }

        $patientsQuery = DB::table('appointments as a')
            ->join('patients as p', 'a.client_id', '=', 'p.id')
            ->join('doctors as d', 'a.doctor_id', '=', 'd.user_id')
            ->whereNotNull('a.zoom_meeting_id')
            ->where('a.zoom_meeting_id', '!=', '')
            ->orderBy('a.id', 'desc')
            ->select(
            'p.id as patient_id',
            'p.first_name as patient_first_name',
            'p.last_name as patient_last_name',
            'p.dob as patient_dob',
            'p.blood_group as patient_blood_group',
            'p.gender as patient_gender',
            'p.marital_status as patient_marital_status',
            'p.email as patient_email',
            'p.phone as patient_phone',
            'p.address as patient_address',
            'a.status as patient_status',
            'd.first_name as doctor_first_name',
            DB::raw("DATE_FORMAT(a.start_time, '%h:%i %p') as schedule_time"),
            'a.id as id',
            'd.last_name as doctor_last_name',
            'a.zoom_meeting_id',
            DB::raw("CONCAT('{$domain}/join-meeting/', COALESCE(a.zoom_meeting_id, ''), '?doctor_access=', COALESCE(a.doctor_access_key, '')) as meeting")
            );

        // Filter by status if provided
        $status = $request->get('status', '');
        if (!empty($status)) {
            $patientsQuery->where('a.status', $status);
        }

        // Filter for non-admin users
        if (Auth::user()->role != 'superadmin' && Auth::user()->role != 'admin') {
            $patientsQuery->where('a.doctor_id', $userid);
        }

        // Apply pagination
        $patients = $patientsQuery->paginate(10);

        // Transform paginated collection to encode hashed ID
        $patients->getCollection()->transform(function ($patient) {
            $hashedId = Hashids::encode($patient->id);
            $patient->hashed_id = $hashedId;
            return $patient;
        });

        $statuscolors = [
            'scheduled'   => 'text-yellow-500',
            'completed'   => 'text-green-500',
            'rescheduled' => 'text-blue-500',
            'canceled'    => 'text-red-500',
        ];

        // Return the view with paginated patients
        return view('assignpatients', ['patients' => $patients, 'statuscolors' => $statuscolors]);
    }


    public function changestatus(Request $request)
    {
        $patient = Appointment::find($request->id);

        if ($patient) {
            // Update appointment status
            $patient->status = $request->status;
            $patient->save();

            // Update customer status if found
            $customer = Patient::find($patient->client_id);

            if ($customer) {
                $customer->status = $request->status;
                $customer->save();
            }

            // If status is 'rescheduled', send Email & SMS
            if (strtolower($request->status) === 'rescheduled') {

                $encryptedProposal = Crypt::encrypt($customer->proposal_number ?? $customer->id);
                $availabilityLink = url("/avalableform/" . $encryptedProposal);
                $honestdomain = config('app.url');

                // Send Email via MSG91
                $emailResponse = Http::withHeaders([
                    'accept' => 'application/json',
                    'authkey' => '400026Aum41tS2Xqb68664708P1',
                    'Content-Type' => 'application/json',
                ])->post('https://control.msg91.com/api/v5/email/send', [
                    'recipients' => [[
                        'to' => [[
                            'name' => "{$customer->first_name} {$customer->last_name}",
                            'email' => $customer->email
                        ]],
                        'cc' => [[
                            'name' => 'WhizzCare',
                            'email' => 'honesthealthcare3@gmail.com'
                        ]],
                        'variables' => [
                            'VAR1' => $customer->first_name,
                            'VAR2' => $customer->email,
                            'VAR3' => $customer->address,
                            'VAR4' => $availabilityLink,
                        ]
                    ]],
                    'from' => [
                        'name' => 'WhizzAct',
                        'email' => 'honesthealthcare@email.whizzactsolutions.com'
                    ],
                    'domain' => 'email.whizzactsolutions.com',
                    'reply_to' => [[
                        'email' => 'honesthealthcare3@gmail.com'
                    ]],
                    'attachments' => [],
                    'template_id' => 'honest_patient_availability_request'
                ]);

                // Clean phone number format (remove non-numeric characters)
                $phone = preg_replace('/[^0-9]/', '', $customer->phone);

                // Send SMS via MSG91 Flow
                $smsResponse = Http::withHeaders([
                    'authkey' => '447742AMCfHYvVexw68079d89P1',
                    'Content-Type' => 'application/json',
                ])->post('https://control.msg91.com/api/v5/flow', [
                    'template_id' => '68491dcad6fc054db65dcf12',
                    'short_url' => true,
                    'recipients' => [[
                        'mobiles' => '91' . $request->phone,
                        'customername' => $customer->first_name,
                        'applicationform' => $availabilityLink,
                    ]]
                ]);

                // Debug Logging
                Log::info('MSG91 SMS Response', [
                    'phone' => $phone,
                    'link' => $availabilityLink,
                    'status' => $smsResponse->status(),
                    'body' => $smsResponse->body(),
                    'json' => $smsResponse->json(),
                ]);

                if (!$emailResponse->successful() || !$smsResponse->successful()) {
                    return redirect()->back()->with('error', 'Status updated but failed to send SMS or Email.');
                }
            }

            return redirect()->back()->with('success', 'Status is updated successfully');
        }

        return redirect()->back()->with('error', 'Appointment not found');
    }

    public function schedule(){
        return view('schedule');
    }
    public function searchpatients(Request $request)
    {
        $term = $request->get('term') ?? $request->get('query');

        $patients = Patient::where('phone', 'LIKE', "%{$term}%")
            ->take(10) // limit results for autocomplete
            ->get();



            return response()->json($patients);
    }

    public function storeschedule(Request $request){
        if(!empty($request->id)){
            $schedule = Schedule::find($request->id);
            if(!$schedule){
                return redirect()->back()->with('error','Doctors not found.');
            }
        }else{
            $schedule = new Schedule;
        }
        $user=Auth::user()->email;
        // if($request->hasFile('reason_consultation_docs')){
        //     $fileName = time(). $request->file('reason_consultation_docs')->getClientOriginalName();
        //     $destinationPath = public_path().'/reason_consultation_docs/';
        //     $request->file('reason_consultation_docs')->move($destinationPath, $fileName);
        //     $schedule->reason_consultation_docs = '/reason_consultation_docs/' . $fileName;
        // }
        // if($request->hasFile('existing_medical_conditions_docs')){
        //     $fileName = time(). $request->file('existing_medical_conditions_docs')->getClientOriginalName();
        //     $destinationPath = public_path().'/existing_medical_conditions_docs/';
        //     $request->file('existing_medical_conditions_docs')->move($destinationPath, $fileName);
        //     $schedule->existing_medical_conditions_docs = '/existing_medical_conditions_docs/' . $fileName;
        // }
        // if($request->hasFile('current_medications_docs')){
        //     $fileName = time(). $request->file('current_medications_docs')->getClientOriginalName();
        //     $destinationPath = public_path().'/current_medications_docs/';
        //     $request->file('current_medications_docs')->move($destinationPath, $fileName);
        //     $schedule->current_medications_docs = '/currect_medications_docs/' . $fileName;
        // }
        // if($request->hasFile('previous_consultations_docs')){
        //     $fileName = time(). $request->file('previous_consultations_docs')->getClientOriginalName();
        //     $destinationPath = public_path().'/previous_consultations_docs/';
        //     $request->file('previous_consultations_docs')->move($destinationPath, $fileName);
        //     $schedule->previous_consultations_docs = '/previous_consultations_docs/' . $fileName;
        // }
        // $schedule->company_name = $request->company_name;
        // $schedule->policy_id = $request->policy_id;
        // $schedule->reason_consultation = $request->reason_consultation;
        // $schedule->existing_medical_conditions = $request->existing_medical_conditions;
        // $schedule->current_medications = $request->current_medications;
        // $schedule->previous_consultations = $request->previous_consultations;
        $schedule->appointment_date = Carbon::parse($request->appointment_date)->format('Y-m-d');

        $schedule->appointment_time_slot = $request->appointment_time_slot;
        $schedule->assign_patient_id = $request->assign_patient_id;
        $patient = Patient::find($request->assign_patient_id);
        $patient->status='pending' ;
        $schedule->appoint_doctor = $request->appoint_doctor;
        $schedule->is_active = 'active';
        $schedule->updated_by = $user;
        $schedule->created_by = $user;
        $patient->save();
        $schedule->save();

        if(!empty($request->id)){
            return back()->with("success",  "Patients  Re-Schedule Successfully");
        }else{
            return back()->with("success","Patients is successfully assign");

        }

    }




}
