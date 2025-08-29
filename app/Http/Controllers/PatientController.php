<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Mail;
use App\Models\Patient;
use App\Models\Patientlog;
use Vinkla\Hashids\Facades\Hashids;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\PatientLogMail;
use App\Mail\CustomerMail;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use App\Services\EncryptService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
class PatientController extends Controller
{
    //

    public function show($id)
    {
        $product = Patient::find($id);
        return response()->json($product);

    }

public function downloadWord()
{
    $records = Patient::all();
    $phpWord = new PhpWord();

    // Title style
    $phpWord->addTitleStyle(1, [
        'bold' => true, 'size' => 20, 'color' => '2E75B6'
    ], [
        'alignment' => Jc::CENTER
    ]);

    $labelStyle = ['bold' => true, 'size' => 10];
    $valueStyle = ['size' => 10, 'color' => '333333'];

    $section = $phpWord->addSection([
        'marginTop' => 800,
        'marginBottom' => 800,
        'marginLeft' => 1200,
        'marginRight' => 1200
    ]);

    $section->addTitle("Patient Records Report", 1);
    $section->addTextBreak(1);

    foreach ($records as $index => $record) {
        $section->addText("Patient #" . ($index + 1), ['bold' => true, 'size' => 12, 'color' => '1F4E79']);
        $section->addTextBreak(0.5);

        $fields = [
            'ID' => $record->id,
            'First Name' => $record->first_name,
            'Last Name' => $record->last_name,
            'Email' => $record->email,
            'Phone' => $record->phone,
            'Gender' => $record->gender,
            'Marital Status' => $record->marital_status,
            'Date of Birth' => $record->dob,
            'Blood Group' => $record->blood_group,
            'Proposal Number' => $record->proposal_number,
            'ID Type' => $record->id_type,
            'ID Number' => $record->id_number,
            'ID Document' => $record->id_document,
            'Third Party Administrator' => $record->third_party_administrator,
            'Insurance Company' => $record->insurance_company_name,
            'Insurance Email' => $record->insurance_company_email,
            'Address' => $record->address,
            'Documents' => $record->documents,
            'Status' => $record->status,
            'Active' => $record->is_active ? 'Yes' : 'No',
        ];

        foreach ($fields as $label => $value) {
            $textRun = $section->addTextRun();
            $textRun->addText("{$label}: ", $labelStyle);
            $textRun->addText($value ?? '—', $valueStyle);
        }

        $section->addTextBreak(1);
        $section->addLine(['weight' => 1, 'width' => 480, 'color' => 'AAAAAA']);
        $section->addTextBreak(1);
    }

    // Export the file
    $fileName = 'Patient_Records_' . date('Ymd_His') . '.docx';
    $tempFile = tempnam(sys_get_temp_dir(), 'word_');
    IOFactory::createWriter($phpWord, 'Word2007')->save($tempFile);

    return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
}



    public function patients(Request $request) {
        $status = $request->get('status', '');
        $patientsQuery = Patient::orderBy('id', 'desc');

        if (!empty($status)) {
            $patientsQuery->where('status', $status);
        }

        $patients = $patientsQuery->paginate(10);

        $patients->transform(function ($patient) {
            $hashedId = Hashids::encode($patient->id);
            $patient->hashed_id = $hashedId;
            return $patient;
        });

        // Define status colors
        $statusColors = [
            'Unassigned' =>  'text-orange-500',
            'scheduled'      => 'text-yellow-500',
            'completed'    => 'text-green-500',
            'rescheduled' => 'text-blue-500',
            'canceled'  => 'text-red-500',
        ];

        return view('patient', [
            'patients' => $patients,
            'statusColors' => $statusColors
        ]);
    }



    public function createpatient(Request $request){
        return view('addpatient');
    }
    public function viewpatient($hashed_id){
        $decoded = Hashids::decode($hashed_id);
        $patientId = $decoded[0];
        $patient = Patient::find($patientId);
        // dd($patient);
        return view('viewpatient', ['patient' => $patient]);
    }
    public function editpatient(Request $request,$hashed_id) {
        $decoded = Hashids::decode($hashed_id);
        $patientId = $decoded[0];
        $patient = Patient::find($patientId);

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found.');
        }
        // dd($patient);
        return view('addpatient', ['patient' => $patient]);
    }
    // public function storepatientlog(Request $request)
    // {
    //     // Validate Request
    //     $request->validate([
    //         'name' => 'required|string',
    //         'email' => 'required|email',
    //         'message' => 'required|string',
    //     ]);

    //     // Save Data to Database
    //     $user = Auth::user()->email;
    //     $patientlog = new Patientlog;
    //     $patientlog->name = $request->name;
    //     $patientlog->email = $request->email;
    //     $patientlog->message = $request->message;
    //     $patientlog->created_by = $user;
    //     $patientlog->updated_by = $user;
    //     $patientlog->save();

    //     // Generate PDF
    //     $pdf = Pdf::loadView('pdf.template', compact('patientlog'));

    //     // Force download prompt (User can save it in the Downloads folder)
    //     return response()->streamDownload(function () use ($pdf) {
    //         echo $pdf->output();
    //     }, "patient_log.pdf");
    // }
    public function customermail(Request $request  )
    {
        $validatedData = $request->validate([
            'avalibledate' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'proposal_number' => 'required'  // Ensure the email is provided
        ]);
    $patient = Patient::where('proposal_number', $request->proposal_number)->first();
     $customer_name = $patient->first_name . ' ' . $patient->last_name;
     $patient->providedate = $request->avalibledate;
      $patient->save();
        // Sending the email
       $response = Http::withHeaders([
            'accept' => 'application/json',
            'authkey' => '400026Aum41tS2Xqb68664708P1', // 🔒 Replace with your actual key
            'content-type' => 'application/json',
        ])->post('https://control.msg91.com/api/v5/email/send', [
            'recipients' => [
                [
                    'to' => [
                        [
                            'name' => 'Sachin',
                            'email' => 'honesthealthcare3@gmail.com',
                        ]
                    ],



                    'variables' => [
                        'VAR1' => $request->customer_name,
                        'VAR2' => $request->proposal_number,
                        'VAR3' => $request->avalibledate,
                        'VAR4' => $request->start_time,
                        'VAR5' => $request->end_time,

                    ]
                ]
            ],
            'from' => [
                'name' => 'WhizzCare',
                'email' => 'honesthealthcare@email.whizzactsolutions.com'
            ],
            'domain' => 'email.whizzactsolutions.com',
            'reply_to' => [
                [
                    'email' => 'honesthealthcare3@gmail.com'
                ]
            ],
            'attachments' => [],
            'template_id' => 'honest_availability_requested'
        ]);

        // Handle response
        if ($response->successful()) {
            // return response()->json(['message' => 'Email sent successfully', 'data' => $response->json()]);
            return redirect()->back()->with('success', 'Your form is submitted Successfully ');
        }

        // return response()->json([
        //     'error' => 'Failed to send email',
        //     'status' => $response->status(),
        //     'body' => $response->body()
        // ], $response->status());


    }
    public function deletepatient($hashed_id){
        $decoded = Hashids::decode($hashed_id);
        $patientId = $decoded[0];
        $patient = Patient::find($patientId);
        $patient->delete();
        return back()->with('success', 'Patient is deleted Sucessfully');

    }

public function storepatient(Request $request)
{
    if (!empty($request->id)) {
        $patient = Patient::find($request->id);
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found.');
        }
    } else {
        $patient = new Patient;
    }

    $userEmail = Auth::user()->email;

    // File uploads
    if ($request->hasFile('id_document')) {
        $fileName = time() . '_' . $request->file('id_document')->getClientOriginalName();
        $destinationPath = public_path('/patients/id_document/');
        $request->file('id_document')->move($destinationPath, $fileName);
        $patient->id_document = '/patients/id_document/' . $fileName;
    }

    if ($request->hasFile('documents')) {
        $fileName = time() . '_' . $request->file('documents')->getClientOriginalName();
        $destinationPath = public_path('/patients/documents/');
        $request->file('documents')->move($destinationPath, $fileName);
        $patient->documents = '/patients/documents/' . $fileName;
    }

    // Assign data
    $patient->first_name = $request->first_name;
    $patient->last_name = $request->last_name;
    $patient->email = $request->email;
    $patient->dob = $request->dob;
    $patient->phone = $request->phone;
    $patient->gender = $request->gender;
    $patient->id_number = $request->id_number;
    $patient->blood_group = $request->blood_group;
    $patient->marital_status = $request->marital_status;
    $patient->proposal_number = $request->proposal_number;
    $patient->insurance_company_name = $request->insurance_company_name;
    $patient->insurance_company_email = $request->insurance_company_email;
    $patient->third_party_administrator = $request->third_party_administrator;
    $patient->id_type = $request->id_type;
    $patient->status = 'Unassigned';
    $patient->address = $request->address;
    $patient->is_active = "active";
    $patient->created_by = $userEmail;
    $patient->updated_by = $userEmail;
    $patient->save();

    $encryptedProposal = Crypt::encrypt($patient->proposal_number);

    $honestdomain = env('APP_URL');
    $availabilityLink = "{$honestdomain}/avalableform/{$encryptedProposal}";
//  if (empty($request->id)) {

    $emailResponse = Http::withHeaders([
        'accept' => 'application/json',
        'authkey' => '400026Aum41tS2Xqb68664708P1',
        'content-type' => 'application/json',
    ])->post('https://control.msg91.com/api/v5/email/send', [
        'recipients' => [[
            'to' => [[
                'name' => "{$request->first_name} {$request->last_name}",
                'email' => $request->email
            ]],
            'cc' => [[
                'name' => 'WhizzCare',
                'email' => 'honesthealthcare3@gmail.com'
            ]],
            'variables' => [
                'VAR1' => $request->first_name,
                'VAR2' => $request->email,
                'VAR3' => $request->address,
                'VAR4' => $availabilityLink,
            ]
        ]],
        'from' => [
            'name' => 'WhizzCare',
            'email' => 'honesthealthcare@email.whizzactsolutions.com'
        ],
        'domain' => 'email.whizzactsolutions.com',
        'reply_to' => [[
            'email' => 'honesthealthcare3@gmail.com'
        ]],
        'attachments' => [],
        'template_id' => 'honest_patient_availability_request'
    ]);

    // Send SMS via MSG91 Flow
    $smsResponse = Http::withHeaders([
        'authkey' => '447742AMCfHYvVexw68079d89P1',
        'Content-Type' => 'application/json',
    ])->post('https://control.msg91.com/api/v5/flow', [
        'template_id' => '68491dcad6fc054db65dcf12',
        'short_url' => 1,
        'recipients' => [[
             'mobiles' => '91' . $request->phone,
            'customername' => $request->first_name,
            'applicationform' => "{$honestdomain}/avalableform/{$encryptedProposal}",
        ]]
    ]);

    // Final response
    if ($emailResponse->successful() && $smsResponse->successful()) {
        return back()->with('success', 'SMS and Email sent successfully!');
    } else {

        return back()->with('error', 'Failed to send SMS or Email.');
    }
// }
}


}
