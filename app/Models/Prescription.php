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

public function scopeSearch($query, $term)
{
    if ($term) {
        $term = "%{$term}%";
        return $query->where(function ($q) use ($term) {
            $q->where('patient_name', 'like', $term)
              ->orWhere('patient_phone', 'like', $term)
              ->orWhereDate('prescription_date', $term);
        });
    }

    return $query; // if no search term, return all
}

}
