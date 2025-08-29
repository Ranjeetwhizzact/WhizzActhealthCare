<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'profile_img',
        'email',
        'phone',
        'gender',
        'proposal_number',
        'marital_status',
        'dob',
        'blood_group',
        'address',
        'status',
      
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $table = 'patients';

}
