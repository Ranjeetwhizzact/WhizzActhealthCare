<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Validation\Rule;
use PDF;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointment;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Models\Prescription;
use Vinkla\Hashids\Facades\Hashids;

class PrescriptionController extends Controller
{
    //

public function prescriptionForm(Request $request, $hashed_id)
{
    // Decode the hashed ID
    $decoded = Hashids::decode($hashed_id);
    if (empty($decoded)) {
        abort(404, 'Invalid ID');
    }

    $appointmentId = $decoded[0];

    // Get the appointment
    $appointment = Appointment::find($appointmentId);
    if (!$appointment) {
        abort(404, 'Appointment not found');
    }

    // Get the patient linked to this appointment
    $patient = Patient::find($appointment->client_id);
    if (!$patient) {
        abort(404, 'Patient not found');
    }

    // Check if the patient has an old prescription (latest one)
    $lastPrescription = Prescription::where('patient_id', $patient->id)
        ->latest('prescription_date') // or ->latest('id') if no date column
        ->first();

    // Pass appointment, patient, and lastPrescription (if any) to the view
    return view('prescriptionform', [
        "appointment" => $appointment,
        "patient" => $patient,
        "lastPrescription" => $lastPrescription
    ]);
}
public function generatePrescription($id)
{
    
    $prescription = Prescription::where('prescription_id', $id)->firstOrFail();

    // If medications are stored as JSON in DB, decode them
    if (is_string($prescription->medications)) {
        $prescription->medications = json_decode($prescription->medications, true);
    }

    // Generate PDF
    $pdf = PDF::loadView('pdf.prescription', compact('prescription'));

    // Stream PDF to browser
    return $pdf->stream('prescription_'.$prescription->prescription_id.'.pdf');
}


public function storerescription(Request $req)
{
    $p = new Prescription();

    $appointment = Appointment::find($req->appointment_id);

    if ($appointment) {
        $client = Patient::find($appointment->client_id);
        $doctor = Doctor::find($appointment->doctor_id);
    } else {
        return back()->withErrors(['appointment_id' => 'Appointment not found']);
    }

    // Generate Prescription No
    $lastPrescription = Prescription::orderBy('prescription_id', 'desc')->first();
    if ($lastPrescription) {
        $lastNumber = (int) substr($lastPrescription->prescription_no, 3);
        $nextNumber = $lastNumber + 1;
    } else {
        $nextNumber = 1;
    }
    $numberPadded = str_pad($nextNumber, 10, '0', STR_PAD_LEFT);

    // Fill Prescription
    $p->appointment_id  = $req->appointment_id;
    $p->prescription_no = 'WA-' . $numberPadded;

    $p->patient_name    = $client->first_name . " " . $client->last_name;
    $p->patient_dob     = $client->dob;

    // ✅ Correct Age Calculation
    if (!empty($client->dob)) {
        $dob = Carbon::parse($client->dob);
        $now = Carbon::now();
        $years = $dob->diffInYears($now);
        $months = $dob->copy()->addYears($years)->diffInMonths($now);
        $p->patient_age = "{$years} years {$months} months";
    }

    $p->patient_gender   = $client->gender;
    $p->patient_phone    = $client->phone;
    $p->patient_email    = $client->email;
    $p->patient_address  = $client->address;
    $p->patient_id       = $client->id;
    $p->physician_name   = $doctor->first_name . " " . $doctor->last_name; // ✅ fixed (you had $client->last_name before)
    $p->physician_phone  = $doctor->phone;
    $p->physician_email  = $doctor->email;
    $p->physician_signature = $doctor->signature;

    $p->allergies        = $req->allergies;
    $p->medications      = json_encode($req->medications);
    $p->notable_condition = $req->notable_condition;

    $p->save();

    return redirect()->back()->with('success', 'Prescription saved successfully.');
}



 public function index(Request $request)
    {
        $search = $request->input('search');

        $prescriptions = Prescription::search($search)
            ->orderBy('prescription_date', 'desc')
            ->paginate(10); // pagination

        return view('viewprescription', compact('prescriptions', 'search'));
    }



}
