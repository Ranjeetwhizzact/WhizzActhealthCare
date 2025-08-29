<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PrescriptionController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('index');

// });



// Public Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth')->group(function () {



    Route::middleware(['role:admin,superadmin'])->group(function () {
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/download-word', [PatientController::class, 'downloadWord'])->name('download.word');
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/edituser/{id}',[AuthController::class,'edituser'])->name('edituser');
        Route::get('/',[HomeController::class,'index'])->name('dashboard');
        Route::get('/createdoctor',[DoctorController::class,'createdoctor']);
        Route::post('/storepatient',[PatientController::class,'storepatient']);
        Route::delete('/deletepatient/{id}',[PatientController::class,'deletepatient'])->name('deletepatient');
        Route::delete('/deletedoctor/{id}',[DoctorController::class,'deletedoctor'])->name('deletedoctor');
        Route::get('/editdoctor/{id}',[DoctorController::class,'editdoctor'])->name('editdoctor');
        Route::post('/storedoctor',[DoctorController::class,'storedoctor'])->name('store.doctor');
        Route::post('/updatedoctor/{id}',[DoctorController::class,'updatedoctor'])->name('update.doctor');;
        Route::post('/storepatientlog',[PatientController::class,'storepatientlog']);
        Route::get('/createpatient',[PatientController::class,'createpatient']);
        Route::get('/editpatient/{id}',[PatientController::class,'editpatient'])->name('editpatient');
        Route::get('/patient', [PatientController::class, 'patients'])->name('patient');
        Route::get('/viewprescriptions/{id}',[PrescriptionController::class,'prescriptionForm'])->name('viewprescriptions');
        Route::post('/storerescription',[PrescriptionController::class,'storerescription']);
        Route::get('/prescription/{id}/pdf', [PrescriptionController::class, 'generatePrescription']);


        Route::post('/getrecording/{id}',[AppointmentController::class,'getRecording'])->name('getRecording');

        Route::get('/schedule', [AppointmentController::class, 'index'])->name('schedule');
        Route::post('/schedule', [AppointmentController::class, 'scheduleCall'])->name('schedule-call');
        Route::get('/join-meeting/{id}', [AppointmentController::class, 'joinMeeting'])
        ->name('join.meeting');


        // Route::get('/viewpatient', [PatientController::class, 'viewpatient'])->name('patient');
        Route::get('/viewpatient/{id}', [PatientController::class, 'viewpatient'])->name('viewpatient');
        Route::get('/patient/{id}', [PatientController::class, 'show'])->name('patient.show');
        Route::post('/storeschedule', [ScheduleController::class, 'storeschedule'])->name('storeschedule');
        Route::post('/storepatientlog', [PatientController::class, 'storepatientlog'])->name('storepatientlog');
        Route::get('/searchpatients', [ScheduleController::class, 'searchpatients']);
        Route::get('/searchuser', [DoctorController::class, 'searchuser']);
        Route::get('/doctor',[DoctorController::class,'doctor']);
        Route::get('/getavailabletimes',[AppointmentController::class,'checkdoctor']);
    });
    Route::post('/storereport',[AppointmentController::class,'storereport'])->name('storereport');
    Route::get('/medical-history',[ScheduleController::class,'medicalHistory'])->name('medical-history');
    Route::get('/video-consultation',[ScheduleController::class,'videoConsultation'])->name('video.consultation');
    Route::get('/report/{id}',[ReportController::class,'report'])->name('report');
    Route::post('/changestatus',[ScheduleController::class,'changestatus'])->name('changestatus');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::get('avalableform/{proposalno}', [HomeController::class, 'avalableform']);
Route::post('customermail', [PatientController::class, 'customermail']);

Route::get('/join-meeting/{id}', [AppointmentController::class, 'joinMeeting'])
    ->name('join.meeting');
