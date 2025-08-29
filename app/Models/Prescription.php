<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;
      protected $fillable = [

        'prescription_no',
        'appointment_id',
        'prescription_date',
        'patient_name',
        'patient_age',
        'patient_dob',
        'patient_gender',
        'patient_phone',
        'patient_email',
        'patient_address',
        'notable_condition',
        'allergies',
        'physician_name',
        'physician_phone',
        'physician_email',
        'medications',
        'created_at',
        'upadated_at'
    ];
    protected $hidden = [
        'created_at',
        'upadated_at'
    ];
    protected $table = 'prescriptions';

}
