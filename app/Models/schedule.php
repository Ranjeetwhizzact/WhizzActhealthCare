<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class schedule extends Model
{
    protected $fillable = [
        'id',
        'company_name',
        'policy_id',
        'reason_consultation',
        'reason_consultation_docs',
        'existing_medical_conditions',
        'existing_medical_conditions_docs',
        'currect_medications',
        'currect_medications_docs',
        'previous_consultations',
        'previous_consultations_docs',
        'appointment_date',
        'appointment_time_slot',
        'assign_patient_name',
        'appoint_doctor',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'updated_by',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $table = 'schedule';
}
