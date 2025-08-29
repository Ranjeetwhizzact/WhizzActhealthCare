<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use App\Models\Prescription;
use Vinkla\Hashids\Facades\Hashids;
class ReportController extends Controller
{
    //
    public function report(Request $request ,$hashed_id){
        $userEmail = Auth::user()->email;
        $decoded = Hashids::decode($hashed_id);
        $patientId = $decoded[0];
        $appoint_id = Appointment::find($patientId);
        $doctor = Doctor::where('email', $userEmail)->first();
        
        if (!$appoint_id) {
            Log::error('Schedule not found', ['id' => $id]);
            return response()->json(['error' => 'Schedule not found'], 404);
        }
        
        // $report =  DB::table('appointments as a')
        // ->join('patients as p', 'a.client_id', '=', 'p.id')
        // ->join('doctors as d', 'a.doctor_id', '=', 'd.user_id')
      
        //     ->where('a.id', '=', $appoint_id->id)
        //     ->select(
        //         'a.id as id',
        //         'p.id as patient_id',
        //         'p.first_name as patient_first_name',
        //         'p.last_name as patient_last_name',
        //         'a.start_time as appointment_date',
        //         'p.dob as patient_dob',
        //         'p.gender as patient_gender',
        //         'p.marital_status as patient_marital_status',
        //         'p.email as patient_email',
        //         'p.phone as patient_phone',
        //         'p.address as patient_address',
        //         'p.status as patient_status',
        //         'd.first_name as doctor_first_name',
        //         'a.start_time as schedule_time',
        //         'd.last_name as doctor_last_name',
        //     )
        //     ->get();
        $report = Prescription::where('appointment_id',$patientId)->get();
        
      
        
        // return $report;
        
    
    // dd($report); 
     // This will dump and die the result to inspect it
    

        return view('report', ['report'=>$report]);
    }
}
