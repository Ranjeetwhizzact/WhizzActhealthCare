<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;

class DoctorController extends Controller
{
    public function createdoctor(Request $request){
        return view('add-doctor');
    }
    public function searchuser(Request $request){
        $term = $request->input('term');
        $users = User::where('email', 'LIKE', "%{$term}%")->get();
        return response()->json($users);
    }
    public function doctorprofile($id){
        $decoded = Hashids::decode($id);
        $doctorId = $decoded[0];
        $doctor = Doctor::find( $doctorId);
        return view('doctorprofile',['doctor'=>$doctor]);
    }
    public function doctor(Request $request)
    {
        $doctors = Doctor::orderBy('id', 'desc')->paginate(10);
        $doctors->transform(function ($doctor) {
            $hashedId = Hashids::encode($doctor->id);
            $doctor->hashed_id = $hashedId;
            $doctor->available_days = json_decode($doctor->available_days, true) ?? [];
            return $doctor;
        });

        // Return to the view
        return view('doctors', ['doctors' => $doctors]);
    }
    
    public function editdoctor($hashed_id) {
        $decoded = Hashids::decode($hashed_id);
        $doctorId = $decoded[0];
        $doctor = Doctor::find($doctorId);
        $user_id =  $doctor->user_id;
        $user = User::find($doctor->user_id);
        // $decryptedPassword = Crypt::decrypt($user->password);
       
        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor not found!');
        }
        $doctor->available_days = json_decode($doctor->available_days, true) ?? [];
// dd($doctor);
    
        return view('add-doctor', ['doctor' => $doctor ,'user'=>$user ]);
    }
    public function deletedoctor($hashed_id){
        $decoded = Hashids::decode($hashed_id);
        $doctorId = $decoded[0];
        $doctor = Doctor::find($doctorId);
        $doctor->delete();
        return back()->with('success', 'Doctor is deleted Sucessfully');
    }
public function storedoctor(Request $request)
{
    // Validate input
 $validator = Validator::make($request->all(), [
    'email' => 'required|email|unique:doctors,email|unique:users,email',
    'first_name' => ['required','regex:/^[A-Za-z\s]+$/','max:255'],
    'last_name' => ['required','regex:/^[A-Za-z\s]+$/','max:255'],
    'phone' => 'required|numeric|max:10',
    'gender' => 'required|string',
    'education' => ['required','regex:/^[A-Za-z\s]+$/','max:255'],
    'available_days' => 'required|array',
    'password'=> 'required|min:6',
    'online_fees'=> 'required|numeric|min:0',
    'offline_fees'=> 'required|numeric|min:0',
    'department'=> ['required','regex:/^[A-Za-z\s]+$/'],
    'associated_hospitals'=> ['required','regex:/^[A-Za-z\s]+$/'],
    'year_of_experince'=>'required|numeric|min:0',
    'image' => 'required|image|mimes:jpeg,png,jpg|max:200',
    'signature' => 'required|image|mimes:png,jpg,jpeg|max:100',
], [
    'first_name.regex' => 'The first name may only contain letters and spaces.',
    'last_name.regex' => 'The last name may only contain letters and spaces.',
    'education.regex' => 'The education field may only contain letters and spaces.',
    'department.regex' => 'The department may only contain letters and spaces.',
    'associated_hospitals.regex' => 'The associated hospitals may only contain letters and spaces.',
]);


    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();

    try {
       
        // Create user
        $user = new User();
        $user->name = $request->first_name . " " . $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

      
        $doctor = new Doctor();
        $doctor->user_id = $user->id;
        $doctor->email = $request->email;
        $doctor->first_name = $request->first_name;
        $doctor->last_name = $request->last_name;
        $doctor->phone = $request->phone;
        $doctor->gender = $request->gender;
        $doctor->reference_number = $request->reference_number;
        $doctor->department = $request->department;
        $doctor->online_fees = $request->online_fees;
        $doctor->offline_fees = $request->offline_fees;
        $doctor->year_of_experince = $request->year_of_experince;
        $doctor->associated_hospitals = $request->associated_hospitals;
        $doctor->education = $request->education;
        $doctor->available_days = json_encode($request->available_days);
        $doctor->created_by = Auth::user()->email ?? 'system';
        $doctor->updated_by = Auth::user()->email ?? 'system';
        $doctor->is_active = 'active';

        // File uploads
        if ($request->hasFile('image')) {
            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('/doctors/profile/'), $fileName);
            $doctor->image = '/doctors/profile/' . $fileName;
            \Log::info('Profile image uploaded', ['path' => $doctor->image]);
        }

        if ($request->hasFile('signature')) {
            $fileName = time() . '_' . $request->file('signature')->getClientOriginalName();
            $request->file('signature')->move(public_path('/doctors/signature/'), $fileName);
            $doctor->signature = '/doctors/signature/' . $fileName;
            \Log::info('Signature uploaded', ['path' => $doctor->signature]);
        }

        if ($request->hasFile('degree')) {
            $fileName = time() . '_' . $request->file('degree')->getClientOriginalName();
            $request->file('degree')->move(public_path('/doctors/degree/'), $fileName);
            $doctor->degree = '/doctors/degree/' . $fileName;
            \Log::info('Degree uploaded', ['path' => $doctor->degree]);
        }

        $doctor->save();

        DB::commit();

        \Log::info('Doctor created successfully', ['doctor_id' => $doctor->id]);

        return redirect()->back()->with('success', 'Doctor created successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error creating doctor', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])->withInput();
    }
}


    public function updatedoctor(Request $request, $id)
{
    // Validate input
    $request->validate([
        'email' => [
            'required', 'email',
            Rule::unique('doctors', 'email')->ignore($id), // Allow the same email for the current doctor
        ],
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone' => 'required|string|max:15',
        'gender' => 'required|string',
        'education' => 'required|string|max:255',
        'available_days' => 'required|array',
    ], [
        'email.unique' => 'This email is already registered with another doctor.',
    ]);

    DB::beginTransaction();

    try {
     

        $doctor = Doctor::findOrFail($id);
        $user = User::findOrFail($doctor->user_id);

        // Update User
        $user->name = $request->first_name . " " . $request->last_name;
        $user->email = $request->username;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update Doctor
        $doctor->email = $request->email;
        $doctor->first_name = $request->first_name;
        $doctor->last_name = $request->last_name;
        $doctor->phone = $request->phone;
        $doctor->gender = $request->gender;
        $doctor->education = $request->education;
        $doctor->reference_number = $request->reference_number;
        $doctor->available_days = json_encode($request->available_days);
        $doctor->updated_by = Auth::user()->email;
        $doctor->save();

        DB::commit();

     

        return redirect()->back()->with('success', 'Doctor updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
      
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

    
}

