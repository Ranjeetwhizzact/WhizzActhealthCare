<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\Patient;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Log;


class HomeComtroller extends Controller
{
    //

    public function index(Request $request){
        $totalCount = DB::table('appointments as a')
        ->join('patients as p', 'a.client_id', '=', 'p.id')
        ->join('doctors as d', 'a.doctor_id', '=', 'd.user_id')
      
        ->count();

        $todayCount =  DB::table('appointments as a')
        ->join('patients as p', 'a.client_id', '=', 'p.id')
        ->join('doctors as d', 'a.doctor_id', '=', 'd.user_id')
      
    ->whereDate('a.start_time', Carbon::today()) 
    ->count();

        $appointments = DB::table('appointments as a')
        ->orderBy('id','desc')
         ->join('patients as p', 'a.client_id', '=', 'p.id')
         ->join('doctors as d', 'a.doctor_id', '=', 'd.user_id')

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
        'p.status as patient_status',
        'd.first_name as doctor_first_name',
        'a.start_time as schedule_time',
        'a.id as id',
        //  'p.profile_img as profile_img',
        'a.start_time as appointment_date',
        'd.last_name as doctor_last_name'
    )
    ->paginate(7); 

    $completedAppointments =  DB::table('appointments as a')
    ->join('patients as p', 'a.client_id', '=', 'p.id')
    ->join('doctors as d', 'a.doctor_id', '=', 'd.user_id')
  
    ->where('p.status','completed')
    ->whereDate('a.start_time', Carbon::today()) 
    
    ->count(); 

    $pendingAppointments =  DB::table('appointments as a')
    ->join('patients as p', 'a.client_id', '=', 'p.id')
    ->join('doctors as d', 'a.doctor_id', '=', 'd.user_id')
    ->where('p.status','scheduled')
    ->whereDate('a.start_time',Carbon::today()) 
    ->count(); 

    $statusColors = [
        'scheduled'   => 'text-yellow-500',
        'completed'   => 'text-green-500',
        'rescheduled' => 'text-blue-500',
        'canceled'    => 'text-red-500',
    ];
        
    $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));
    $dayappointments =DB::table('appointments as a')
    ->join('patients as p', 'a.client_id', '=', 'p.id')
    ->join('doctors as d', 'a.doctor_id', '=', 'd.user_id')
    ->whereDate('a.start_time', $selectedDate)
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
        'p.status as patient_status',
        'd.first_name as doctor_first_name',
        'a.start_time as schedule_time',
        'a.id as id',
        // 'p.profile_img as profile_img',
        // 'd.doctor_img as doctor_profile',
        'a.start_time as appointment_date',
        'd.last_name as doctor_last_name'
    )
    ->paginate(10); 
    $appointments->transform(function ( $appointments) {
        $hashedId = Hashids::encode( $appointments->patient_id);
        $appointments->hashed_id = $hashedId;
        return $appointments;
    });
    // dd($dayappointments);
        return view('index',
        [
            'selectedDate'=>$selectedDate,
            'dayappointments'=> $dayappointments,
            'totalCount'=>$totalCount,
            'todayCount'=>$todayCount,
            'pendingAppointments'=>$pendingAppointments,
            'completedAppointments'=>$completedAppointments,
            'appointments'=>$appointments,
            'statusColors' => $statusColors
        ]);
    }
   

 

  public function avalableForm($proposaldecode)
{
    $proposaldecode = Crypt::decrypt($proposaldecode);

    return view('avalableform', ['proposaldecode' => $proposaldecode]);
}
    
   
  
  
}
